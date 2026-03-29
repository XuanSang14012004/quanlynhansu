<?php

namespace App\Models;

// 1. Chỉ giữ lại Interface và Trait mới
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

// --- XÓA DÒNG DƯỚI NÀY ĐI (Đây là nguyên nhân gây lỗi) ---
// use Laratrust\Traits\LaratrustUserTrait; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

// Class User implements LaratrustUser
class User extends Authenticatable implements LaratrustUser
{
    // --- XÓA DÒNG DƯỚI NÀY ĐI (Nguyên nhân gây lỗi) ---
    // use LaratrustUserTrait;

    // 2. CHỈ DÙNG DÒNG NÀY LÀ ĐỦ
    use HasRolesAndPermissions;

    use HasFactory, Notifiable;
    // use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'role',
        'phongban_id',
        'bophan_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'phongban_id');
    }

    public function boPhan()
    {
        return $this->belongsTo(BoPhan::class, 'bophan_id');
    }

    public function featureGroups()
    {
        return $this->belongsToMany(FeatureGroup::class, 'feature_group_user');
    }
    public function nhanSu()
    {
        // tham số thứ 2 là khóa ngoại (email bên bảng nhan_sus)
        // tham số thứ 3 là khóa nội (email bên bảng users)
        return $this->hasOne(\App\Models\NhanSu::class, 'email', 'email');
    }

    

    // 2. HÀM QUAN TRỌNG: Kiểm tra quyền thông qua nhóm
    public function hasPermissionViaGroup($permissionName)
    {
        // Lấy tất cả các nhóm mà user này tham gia
        // Eager load 'permissions' để tránh truy vấn N+1
        $groups = $this->featureGroups()->with('permissions')->get();

        foreach ($groups as $group) {
            // Nếu nhóm này chứa quyền đang cần tìm
            if ($group->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

        return false;
    }
}
