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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_question'); // Referencia a la tabla questions
            $table->string('respuesta'); // Columna respuesta
            $table->unsignedBigInteger('id_question_type'); // Referencia a la tabla question_type
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_form');
            $table->unsignedBigInteger('id_schedule')->nullable();

            // Claves foráneas
            $table->foreign('id_question')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('id_question_type')->references('id')->on('question_type')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_form')->references('id')->on('forms')->onDelete('cascade');
            $table->foreign('id_schedule')->references('id')->on('schedules')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
