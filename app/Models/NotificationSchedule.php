<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSchedule extends Model
{
     protected $table = 'notification_schedules';

    protected $fillable = [
        'schedule_type',
        'notify_time',
        'email',
        'zalo',
        'phone',
        'user_id'
    ];
    public function user()
{
    return $this->belongsTo(User::class);
}
}
