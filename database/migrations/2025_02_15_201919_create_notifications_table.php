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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->string('url')->nullable();
            $table->string('email')->nullable();
            $table->string('nombre')->nullable();
            $table->string('apellidos')->nullable();
            $table->text('duda');
            $table->boolean('read')->default(false);
            $table->string('tipo')->default('normal');
            $table->foreignIdFor(\App\Models\ShiftExchange::class)->nullable()->constrained()->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
