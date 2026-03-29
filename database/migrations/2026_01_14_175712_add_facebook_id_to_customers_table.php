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
    Schema::table('customers', function (Blueprint $table) {
        $table->string('facebook_id')->nullable()->after('email');
        // Nếu bảng customers chưa có avatar thì bỏ comment dòng dưới
        // $table->string('avatar')->nullable()->after('facebook_id');
    });
}

public function down()
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropColumn('facebook_id');
        // $table->dropColumn('avatar');
    });
}
};
