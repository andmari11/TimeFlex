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
        Schema::create('shift_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(\App\Models\Schedule::class);
            $table->string('notes')->nullable();
            $table->timestamp("start");
            $table->timestamp("end");
            $table->integer("users_needed");
            $table->integer("period");
            $table->boolean("weekends_excepted");
        });
        Schema::create('shift_type_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\ShiftType::class, 'shift_type_id');
            $table->foreignIdFor(\App\Models\User::class, 'user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_types');
        Schema::dropIfExists('shift_type_user');
    }
};
