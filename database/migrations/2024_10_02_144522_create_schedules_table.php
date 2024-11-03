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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Company::class);
            $table->timestamps();

            $table->string("name");
            $table->string("description")->nullable();
            $table->json("scheduleJSON")->nullable();
            $table->string("status")->default("pending");



        });
        Schema::create('schedules_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Schedule::class);;
            $table->foreignIdFor(\App\Models\User::class);;
            $table->timestamps();

            $table->unique(['schedule_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('schedules_users');

    }
};
