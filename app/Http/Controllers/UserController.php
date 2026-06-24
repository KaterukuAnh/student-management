<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Danh sách tài khoản
    public function index(Request $request)
    {
        $perPage = max(1, min((int) $request->input('per_page', 10), 100));
        $users = User::latest()->paginate($perPage)->withQueryString();
        return view('users.index', compact('users'));
    }

    // Form tạo mới
    public function create()
    {
        return view('users.create');
    }

    // Lưu vào DB — luôn tạo role 'teacher', mật khẩu tự sinh
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|min:2',
            'email' => 'required|email|unique:users',
        ]);

        $password = Str::password(12);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($password),
            'role'     => 'teacher',
        ]);

        return redirect()->route('users.index')
            ->with('success', __('Tạo tài khoản giáo viên thành công!'))
            ->with('generated_user', $user->only('name', 'email'))
            ->with('generated_password', $password);
    }

    // Form sửa
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Cập nhật — chỉ tên và email, không đổi role/mật khẩu ở đây
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|min:2',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('users.index')
                         ->with('success', __('Cập nhật thành công!'));
    }

    // Xóa
    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, __('Không thể tự xóa tài khoản của chính bạn.'));

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', __('Xóa thành công!'));
    }
}
