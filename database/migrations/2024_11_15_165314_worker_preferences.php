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
        Schema::create('worker_preferences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(\App\Models\Form::class);
            $table->foreignIdFor(\App\Models\User::class );
            $table->json('holidays')->nullable();
            $table->integer('holidays_weight')->nullable();
            $table->json('preferred_shift_types')->nullable();
            $table->integer('preferred_shift_types_weight')->nullable();
            $table->json('past_satisfaction')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_preferences');

    }
};
