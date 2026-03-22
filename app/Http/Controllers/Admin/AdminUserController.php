<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        // Lấy tất cả người dùng không ngoại trừ ai cả
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        // Dùng with('courses') để Laravel tự động lấy các khóa học liên quan qua bảng trung gian
        $user = User::with('courses')->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Kiểm tra xem có đang tự sửa vai trò của chính mình không (để tránh tự tước quyền admin)
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Bạn không thể tự thay đổi vai trò của chính mình!');
        }

        $request->validate([
            'role' => 'required|in:user,admin', // Chỉ chấp nhận 1 trong 2 giá trị này
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật vai trò cho ' . $user->name);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng!');
    }
}
