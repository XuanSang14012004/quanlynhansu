<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureGroup extends Model
{
    use HasFactory;

    protected $table = 'feature_groups';
    protected $fillable = ['name', 'description'];

    // Quan hệ: Một nhóm có nhiều quyền
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'feature_group_permission');
    }

    public function users()
{
    return $this->belongsToMany(User::class, 'feature_group_user');
}
}
