<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PhongBan;
use App\Models\BoPhan;
use App\Models\LoginHistory;
use App\Models\NhanSu;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // <--- 2. Lấy 5-10 hoạt động gần nhất của user này
        $activities = LoginHistory::where('user_id', $user->id)
            ->latest() // Sắp xếp mới nhất trước
            ->take(10) // Lấy 10 dòng
            ->get();

        // <--- 3. Truyền biến $activities sang view
        return view('profile.index', compact('user', 'activities'));
    }

    public function edit()
    {
        $user = Auth::user();
        $phongBans = PhongBan::all();
        $boPhans = BoPhan::all();

        // Lấy thông tin nhân sự hiện tại để fill vào form (nếu cần)
        $nhanSu = NhanSu::where('email', $user->email)->first();

        return view('profile.edit', [
            'user' => $user,
            'nhanSu' => $nhanSu, // Truyền thêm biến này sang view để hiển thị phòng ban hiện tại
            'phongBans' => $phongBans,
            'boPhans' => $boPhans
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $oldEmail = $user->email;

        // --- PHẦN CHỈNH SỬA: Thêm mảng thông báo lỗi và validate chặt hơn ---
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            // Sửa dòng này: bắt buộc là số, từ 10-11 ký tự
            'phone' => 'required|numeric|digits_between:10,11',
            'phongban_id' => 'nullable|integer',
            'bophan_id' => 'nullable|integer',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            // Thêm thông báo tiếng Việt để hiển thị ra View
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.numeric' => 'Số điện thoại chỉ được chứa các chữ số.',
            'phone.digits_between' => 'Số điện thoại phải có độ dài từ 10 đến 11 số.',
            'name.required' => 'Họ tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
        ]);
        // -------------------------------------------------------------------

        // 1. CẬP NHẬT BẢNG USERS
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone, // Không cần check null vì đã required ở trên
            'phongban_id' => $request->phongban_id ?? 0,
            'bophan_id'   => $request->bophan_id ?? 0,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);

        // 2. CẬP NHẬT BẢNG NHAN_SUS
        $nhanSu = NhanSu::where('email', $oldEmail)->first();

        if ($nhanSu) {
            $nhanSu->update([
                'ho_ten' => $request->name,
                'email' => $request->email,
                'dien_thoai' => $request->phone,
                'phongban_id' => $request->phongban_id,
                'bophan_id' => $request->bophan_id,
            ]);
        }

        return back()->with('status', 'Cập nhật hồ sơ và thông tin nhân sự thành công!');
    }
}
