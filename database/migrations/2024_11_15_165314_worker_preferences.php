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
            //foreing id de formulario TODO
            $table->foreignIdFor(\App\Models\User::class );
            $table->json('holidays')->nullable();
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
