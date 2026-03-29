<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
    {
        // Vẫn giữ lệnh này để xóa cái bảng bị lỗi dở dang vừa rồi
        Schema::dropIfExists('task_user');

        Schema::create('task_user', function (Blueprint $table) {
            $table->id();
            
            // 1. Dành cho bảng tasks: Bắt buộc dùng unsignedBigInteger (BIGINT)
            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            
            // 2. Dành cho bảng users: Bắt buộc dùng unsignedInteger (INT)
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_user');
    }
};
