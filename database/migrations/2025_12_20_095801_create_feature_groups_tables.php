<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeatureGroupsTables extends Migration
{
    public function up()
    {
        // 1. Bảng Nhóm chức năng (VD: Quản trị tin tức, Kế toán...)
        Schema::create('feature_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // Tên nhóm
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Bảng trung gian: Nhóm chức năng - Quyền (Many-to-Many)
        Schema::create('feature_group_permission', function (Blueprint $table) {
            $table->integer('feature_group_id')->unsigned();
            $table->integer('permission_id')->unsigned();

            // Khóa ngoại (quan trọng để xóa nhóm thì xóa luôn liên kết)
            $table->foreign('feature_group_id')->references('id')->on('feature_groups')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            $table->primary(['feature_group_id', 'permission_id']);
        });

        // 3. Đảm bảo bảng permissions có cột để gom nhóm hiển thị (quan trọng để giống ảnh)
        if (!Schema::hasColumn('permissions', 'group_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('group_name')->nullable()->after('display_name');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('feature_group_permission');
        Schema::dropIfExists('feature_groups');
    }
}