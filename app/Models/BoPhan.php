<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoPhan extends Model
{
    public function scopeGetByPhongBanId($query, $id)
    {
        return $query->where('phongban_id', $id);
    }
}
