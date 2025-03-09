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
        Schema::create('forms', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key, auto-incremental, único, no nulo
            $table->unsignedBigInteger('id_user'); // Foreign key -> Revisar para que varios users tengan acceso al mismo formulario
            $table->string('title'); // Título de la encuesta
            $table->text('summary'); // Resumen del contenido
            //$table->boolean('status')->default(false); // Estado de la encuesta (completada o no)
            $table->timestamp('start_date')->nullable(); // Fecha y hora de inicio de acceso
            $table->timestamp('end_date')->nullable(); // Fecha y hora de fin de acceso

            // Definir la foreign key
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
