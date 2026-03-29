<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //
    protected $table = 'news';
    protected $fillable = ['title', 'slug',  'content', 'thumbnail', 'author_id', 'status', 'published_at','category_id'];


    // Liên kết: Bài viết thuộc về 1 Danh mục
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }
    // Quan hệ với người dùng
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Helper hiển thị trạng thái
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 0: return '<span class="label label-warning">Chờ phê duyệt</span>';
            case 1: return '<span class="label label-success">Đã đăng</span>';
            case 2: return '<span class="label label-danger">Từ chối</span>';
            default: return 'N/A';
        }
    }
}
