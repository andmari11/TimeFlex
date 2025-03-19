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
        Schema::create('shift_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demander_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_receiver_id')->constrained('shifts')->onDelete('cascade');
            $table->foreignId('shift_demander_id')->constrained('shifts')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_exchanges');
    }
};
