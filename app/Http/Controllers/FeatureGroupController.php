<?php

namespace App\Http\Controllers;

use App\Models\FeatureGroup;
use App\Models\Permission;
use Illuminate\Http\Request;

class FeatureGroupController extends Controller
{
    //

    public function index(Request $request)
    {
        // 1. Lấy danh sách nhóm (đã có sẵn trong DB)
        $groups = FeatureGroup::all();

        // 2. Lấy danh sách chức năng (để hiện bên phải)
        $allPermissions = Permission::all()->groupBy('group_name');

        // 3. Xử lý khi bấm chọn 1 nhóm
        $selectedGroup = null;
        $currentPermissions = [];

        if ($request->has('group_id')) {
            $selectedGroup = FeatureGroup::find($request->group_id);
            if ($selectedGroup) {
                $currentPermissions = $selectedGroup->permissions->pluck('id')->toArray();
            }
        } 
        // Mặc định chọn nhóm đầu tiên nếu chưa chọn gì (để giống ảnh là luôn hiển thị nội dung)
        elseif ($groups->count() > 0) {
            $selectedGroup = $groups->first();
            $currentPermissions = $selectedGroup->permissions->pluck('id')->toArray();
        }

        return view('feature_group.index', compact('groups', 'allPermissions', 'selectedGroup', 'currentPermissions'));
    }

    public function update(Request $request, $id)
    {
        $group = FeatureGroup::findOrFail($id);
        $group->permissions()->sync($request->input('permissions', []));
        
        // Redirect lại đúng trang đó và giữ nguyên nhóm đang chọn
        return redirect()->route('feature-group.index', ['group_id' => $id])
                         ->with('success', 'Đã cập nhật chức năng cho nhóm: ' . $group->name);
    }
}
