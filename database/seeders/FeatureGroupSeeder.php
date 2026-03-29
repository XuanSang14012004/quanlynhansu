<?php

namespace Database\Seeders;

use App\Models\FeatureGroup;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureGroupSeeder extends Seeder
{
    public function run()
    {
        // 1. CẬP NHẬT GROUP_NAME CHO PERMISSIONS (để hiển thị cây thư mục bên phải)
        $mapPermissions = [
            'Hệ thống' => ['dashboard', 'company', 'file-manager', 'profile'],
            'Quản trị người dùng' => ['users', 'acl'], 
            'Quản trị nhân sự' => ['nhan-su'],
            'Quản trị hợp đồng' => ['hop-dong'],
            'Quản trị quyết định' => ['quyet-dinh'],
            'Quản trị tin tức' => ['news'], // Gom các quyền news vào nhóm này
        ];

        foreach ($mapPermissions as $groupName => $keywords) {
            foreach ($keywords as $keyword) {
                Permission::where('name', 'LIKE', "%-$keyword%")
                    ->orWhere('name', 'LIKE', "$keyword-%")
                    ->update(['group_name' => $groupName]);
            }
        }
        Permission::where('name', 'read-dashboard')->update(['group_name' => 'Hệ thống']);

        // 2. TẠO CÁC NHÓM CHỨC NĂNG (CỘT TRÁI)
        $groups = [
            [
                'name' => 'Quản trị hệ thống', 
                'description' => 'Cấu hình hệ thống, người dùng',
                'auto_permissions' => ['users', 'acl', 'company', 'dashboard']
            ],
            [
                'name' => 'Quản trị tin tức', // <-- ĐÃ SỬA: Đúng tên bạn yêu cầu
                'description' => 'Đăng bài, sửa bài, duyệt tin',
                'auto_permissions' => ['news'] // Tự động gán quyền news
            ],
            [
                'name' => 'Quản trị nhân sự', 
                'description' => 'Quản lý hồ sơ nhân viên',
                'auto_permissions' => ['nhan-su', 'profile']
            ],
            [
                'name' => 'Quản trị Hợp đồng', 
                'description' => 'Hợp đồng, quyết định nhân sự',
                'auto_permissions' => ['hop-dong', 'quyet-dinh']
            ],
            [
                'name' => 'Quản trị tối cao', // <--- NHÓM BẠN CẦN
                'description' => 'Có toàn bộ quyền hạn trong hệ thống',
                'auto_permissions' => ['*'] // Ký hiệu đặc biệt: Lấy tất cả
            ],
            [
                'name' => 'Quyền cơ bản', 
                'description' => 'Quyền mặc định cho nhân viên',
                'auto_permissions' => ['read-dashboard', 'read-profile']
            ]
        ];

        foreach ($groups as $groupData) {
            // Tìm theo tên: Nếu có "Quản trị tin tức" rồi thì lấy, chưa có thì tạo mới
            $group = FeatureGroup::firstOrCreate(
                ['name' => $groupData['name']],
                ['description' => $groupData['description']]
            );

            // Tự động tích quyền vào nhóm
            $keywords = $groupData['auto_permissions'];
            $permissionIds = Permission::where(function($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('name', 'LIKE', "%$keyword%");
                }
            })->pluck('id')->toArray();

            $group->permissions()->syncWithoutDetaching($permissionIds);
        }
    }
}