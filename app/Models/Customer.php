<?php

namespace App\Models;

// QUAN TRỌNG: Phải dùng Authenticatable thay vì Model thường
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'google_id','facebook_id','zalo_id', 'avatar', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    
    public function hasRole($role)
    {
        return false; 
    }

    /**
     * Hàm giả lập check Permission (nếu hệ thống có gọi)
     * Chỉ cho phép quyền 'bid-search', còn lại từ chối hết.
     */
    public function can($ability, $arguments = [])
    {
        if ($ability == 'bid-search') {
            return true;
        }
        return false;
    }
    
    // Nếu hệ thống dùng hàm hasPermission thay vì can
    public function hasPermission($permission)
    {
        if ($permission == 'bid-search') {
            return true;
        }
        return false;
    }
}