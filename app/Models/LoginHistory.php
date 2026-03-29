<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{

    protected $table = 'login_histories';
    
    protected $fillable = ['user_id', 'action', 'ip_address', 'user_agent'];

    // 2. Liên kết với User để lấy tên người đăng nhập
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}