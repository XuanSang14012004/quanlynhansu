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
        Schema::create('feature_group_user', function (Blueprint $table) {
           $table->integer('user_id')->unsigned();
        $table->integer('feature_group_id')->unsigned();

        // Khóa ngoại
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('feature_group_id')->references('id')->on('feature_groups')->onDelete('cascade');
        
        $table->primary(['user_id', 'feature_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_group_user');
    }
};
