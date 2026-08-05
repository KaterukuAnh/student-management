<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    // Điểm của chính học sinh đang đăng nhập — student_id lấy từ token, không nhận từ client.
    public function index(Request $request)
    {
        $studentId = $request->user()->student_id;

        abort_if(! $studentId, 404, __('Tài khoản chưa liên kết với hồ sơ học sinh.'));

        $grades = Grade::with('subject')
            ->where('student_id', $studentId)
            ->orderByDesc('semester')
            ->get()
            ->map(fn (Grade $grade) => [
                'id' => $grade->id,
                'subject' => $grade->subject->name,
                'semester' => $grade->semester,
                'oral_score' => $grade->oral_score,
                'quiz15_score' => $grade->quiz15_score,
                'test45_score' => $grade->test45_score,
                'final_score' => $grade->final_score,
                'score' => $grade->score,
            ]);

        return response()->json(['data' => $grades]);
    }
}
