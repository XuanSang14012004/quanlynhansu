<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks'; // Khai báo rõ tên bảng

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'assignee_id',
        'creator_id',
        'status',
        'attachment',
        'completion_note',
        'completed_at'
    ];

    // Quan hệ: Một Task thuộc về một User (người làm)
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }

    // Quan hệ: Một Task được tạo bởi một User
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
