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
        Schema::create('questions', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('id_form');
            $table->string('title', 100);
            $table->unsignedBigInteger('id_question_type');

            $table->foreign('id_form')->references('id')->on('forms')->onDelete('cascade');
            $table->foreign('id_question_type')->references('id')->on('question_type')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
