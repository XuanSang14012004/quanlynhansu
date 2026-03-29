<?php

namespace App\Http\Controllers;

use App\Models\FeatureGroup;
use App\Models\PhongBan;
use App\Models\User;
use Illuminate\Http\Request;

class UserFeatureGroupController extends Controller
{
    //

    public function index()
    {
        $groups = FeatureGroup::all();
        $phongBans = PhongBan::all(); // Lấy danh sách phòng ban cho dropdown
        return view('user_feature_group.index', compact('groups', 'phongBans'));
    }

    // Hàm Ajax xử lý việc đổ dữ liệu vào 2 ô select box
    public function getData(Request $request)
    {
        $groupId = $request->group_id;
        $phongBanId = $request->phong_ban_id;

        // --- XỬ LÝ DANH SÁCH USER TRONG NHÓM (CỘT PHẢI) ---
        if ($groupId) {
            // Nếu có chọn nhóm thì lấy user trong nhóm đó
            $usersInGroup = User::whereHas('featureGroups', function ($q) use ($groupId) {
                $q->where('feature_groups.id', $groupId);
            })->get(['id', 'name', 'email']);
        } else {
            // Nếu chưa chọn nhóm thì cột phải để rỗng
            $usersInGroup = [];
        }

        // --- XỬ LÝ DANH SÁCH USER CÓ SẴN (CỘT TRÁI) ---
        if ($groupId) {
            // Nếu ĐÃ chọn nhóm -> Lấy user CHƯA thuộc nhóm đó
            $query = User::whereDoesntHave('featureGroups', function ($q) use ($groupId) {
                $q->where('feature_groups.id', $groupId);
            });
        } else {
            // Nếu CHƯA chọn nhóm -> Lấy TẤT CẢ user (để hiện ngay từ đầu)
            $query = User::query();
        }

        // --- LỌC THEO PHÒNG BAN (Giữ nguyên logic sửa lỗi trước đó của bạn) ---
        if ($phongBanId) {
            $query->where('phongban_id', $phongBanId);
        }

        $usersAvailable = $query->get(['id', 'name', 'email']);

        return response()->json([
            'in_group' => $usersInGroup,
            'available' => $usersAvailable
        ]);
    }

    public function update(Request $request)
    {
        $groupId = $request->group_id;
        $userIds = $request->users ?? []; // Mảng ID user bên cột phải

        $group = FeatureGroup::findOrFail($groupId);

        // Sync: Cái nào có trong mảng thì giữ, không có thì xóa, chưa có thì thêm
        $group->users()->sync($userIds);

        return back()->with('success', 'Đã cập nhật danh sách người dùng cho nhóm thành công!');
    }
}
