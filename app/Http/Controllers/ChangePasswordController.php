<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <--- BẠN ĐANG THIẾU DÒNG NÀY
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    //

    // Nhóm route yêu cầu đăng nhập mới được truy cập
    public function index()
    {
        return view('login.change-password');
    }

    // Xử lý logic đổi mật khẩu
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // confirmed: yêu cầu phải có trường new_password_confirmation
        ]);

        // 2. Kiểm tra mật khẩu cũ có đúng không
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác!']);
        }

        // 3. Cập nhật mật khẩu mới
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('status', 'Đổi mật khẩu thành công!');
    }
}
