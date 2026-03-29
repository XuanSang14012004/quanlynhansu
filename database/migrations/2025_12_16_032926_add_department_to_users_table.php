<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Thêm 2 cột mới, cho phép null (để tránh lỗi dữ liệu cũ)
        $table->integer('phongban_id')->nullable()->default(0);
        $table->integer('bophan_id')->nullable()->default(0);
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['phongban_id', 'bophan_id']);
    });
}
};
