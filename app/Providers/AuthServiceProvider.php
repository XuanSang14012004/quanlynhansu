<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Customer;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        // KIỂM TRA QUYỀN TẬP TRUNG (3 LỚP BẢO VỆ)
        Gate::before(function ($user, $ability) {

            // ----------------------------------------------------
            // LỚP 1: SIÊU QUẢN TRỊ (Quyền lực tối cao)
            // ----------------------------------------------------
            if ($user->hasRole('superadministrator')) {
                return true;
            }

            // ----------------------------------------------------
            // LỚP 2: NHÓM CHỨC NĂNG (Hệ thống mới bạn vừa làm)
            // ----------------------------------------------------
            // Kiểm tra xem User có quyền này thông qua việc được gán vào Nhóm không
            if (method_exists($user, 'hasPermissionViaGroup') && $user->hasPermissionViaGroup($ability)) {
                return true;
            }

            // ----------------------------------------------------
            // LỚP 3: VAI TRÒ CŨ (Laratrust Roles & Permissions)
            // ----------------------------------------------------
            // Đây là bước quan trọng để cứu tài khoản "User" mới tạo!
            // Chúng ta ép buộc kiểm tra: "User này có permission đó thông qua Role cũ không?"
            // Hàm hasPermission() là của Laratrust (có sẵn nhờ Trait HasRolesAndPermissions)
            if ($user->hasPermission($ability)) {
                return true;
            }

            // Nếu qua cả 3 lớp mà không ai xác nhận -> CHẶN (return false)
            // Hoặc return null để Laravel thử các Gate khác (nhưng thường đến đây là hết rồi)
        });

        
    }
}
