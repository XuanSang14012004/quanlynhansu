<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    public function up()
    {
        // 1. TẠO BẢNG DANH MỤC TRƯỚC (Để bảng News có cái mà liên kết)
        Schema::create('news_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); // Tên danh mục (vd: Công nghệ)
            $table->string('slug')->unique(); // Slug (vd: cong-nghe)
            $table->boolean('active')->default(1); // Trạng thái bật/tắt
            $table->timestamps();
        });

        // 2. TẠO BẢNG TIN TỨC
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('thumbnail')->nullable();

            // --- Cột liên kết danh mục ---
            $table->unsignedBigInteger('category_id')->nullable(); 
            // Tạo khóa ngoại: Nếu xóa danh mục, bài viết sẽ set category_id về null
            $table->foreign('category_id')->references('id')->on('news_categories')->onDelete('set null');
            
            // --- Cột tác giả ---
            $table->unsignedBigInteger('author_id');
            
            $table->tinyInteger('status')->default(0); // 0: Chờ duyệt, 1: Đã đăng, 2: Từ chối
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
        
        // Tạo dữ liệu mẫu luôn cho danh mục để đỡ phải nhập tay (Seeding nhanh)
        DB::table('news_categories')->insert([
            ['name' => 'Tin Công nghệ', 'slug' => 'tin-cong-nghe', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tin Đời sống', 'slug' => 'tin-doi-song', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sự kiện công ty', 'slug' => 'su-kien-cong-ty', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        // Xóa bảng con trước, bảng cha sau
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_categories');
    }
}