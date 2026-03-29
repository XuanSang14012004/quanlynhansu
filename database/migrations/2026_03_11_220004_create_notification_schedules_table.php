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
        Schema::create('notification_schedules', function (Blueprint $table) {
        $table->id();

        $table->enum('schedule_type', ['before_day','same_day']);

        $table->time('notify_time');

        $table->boolean('email')->default(false);

        $table->boolean('zalo')->default(false);

        $table->boolean('phone')->default(false);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_schedules');
    }
};
