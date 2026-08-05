<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Factory;

class AuthController extends Controller
{
    // App di động đăng nhập qua Firebase Auth SDK (Google là 1 provider) và gửi lên Firebase ID Token.
    private function firebaseAuth(): FirebaseAuth
    {
        $factory = new Factory;
        $credentials = config('services.firebase.credentials');

        $factory = file_exists($credentials)
            ? $factory->withServiceAccount($credentials)
            : $factory->withProjectId(config('services.firebase.project_id'));

        return $factory->createAuth();
    }

    public function firebase(Request $request)
    {
        $request->validate(['id_token' => 'required|string']);

        // verifyIdToken() ném exception cho token sai định dạng/hết hạn/ký sai, không chỉ trả false.
        try {
            $verifiedToken = $this->firebaseAuth()->verifyIdToken($request->input('id_token'));
        } catch (\Throwable $e) {
            return response()->json(['message' => __('Firebase ID Token không hợp lệ.')], 401);
        }

        $claims = $verifiedToken->claims();
        $firebaseUid = $claims->get('sub');
        $email = $claims->get('email');

        // Bước 1: email phải khớp đúng một học sinh đã có trong hệ thống.
        $student = Student::where('email', $email)->first();
        if (! $student) {
            return response()->json([
                'message' => __('Email này chưa được đăng ký trong hệ thống. Vui lòng liên hệ Admin để được hỗ trợ.'),
            ], 403);
        }

        // Bước 2: tìm user hiện có — ưu tiên firebase_uid, sau đó student_id, cuối cùng email.
        $user = User::where('firebase_uid', $firebaseUid)->first()
            ?? User::where('student_id', $student->id)->first()
            ?? User::where('email', $email)->where('role', 'student')->first();

        if (! $user) {
            // Lần đầu đăng nhập: tạo user mới, liên kết đúng student_id.
            $user = User::create([
                'name'         => $claims->get('name', $email),
                'email'        => $email,
                'password'     => Hash::make(Str::random(32)),
                'role'         => 'student',
                'firebase_uid' => $firebaseUid,
                'student_id'   => $student->id,
            ]);
        } else {
            // Đăng nhập lại: đồng bộ firebase_uid và student_id nếu lệch.
            $updates = [];
            if ($user->firebase_uid !== $firebaseUid) {
                $updates['firebase_uid'] = $firebaseUid;
            }
            if ($user->student_id !== $student->id) {
                $updates['student_id'] = $student->id;
            }
            if ($updates) {
                $user->update($updates);
            }
        }

        if (! $user->isStudent()) {
            return response()->json(['message' => __('Tài khoản này không phải học sinh.')], 403);
        }

        return response()->json([
            'token' => $user->createToken('mobile-app')->plainTextToken,
            'user'  => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'student_id' => $user->fresh()->student_id,
            ],
        ]);
    }
}
