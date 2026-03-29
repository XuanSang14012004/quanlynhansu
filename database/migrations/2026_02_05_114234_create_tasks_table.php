<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tên công việc
            $table->text('description')->nullable(); // Mô tả
            
            // Thời gian
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            // Liên kết với bảng users của bạn
            // assignee_id: Người được giao việc
            $table->unsignedInteger('assignee_id');
            // creator_id: Người tạo việc
            $table->unsignedInteger('creator_id');
            
            // Trạng thái: 0=Chờ xử lý, 1=Hoàn thành, 2=Hết hạn
            $table->tinyInteger('status')->default(0);
            
            // File đính kèm & Ghi chú hoàn thành
            $table->string('attachment')->nullable();
            $table->text('completion_note')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();

            // TẠO KHÓA NGOẠI (Quan trọng)
            // Liên kết cột assignee_id với id của bảng users
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};