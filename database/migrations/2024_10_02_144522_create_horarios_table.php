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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Empresa::class);
            $table->json('horario');
            $table->timestamps();
            $table->string("nombre");
            $table->string("descripcion")->nullable();

        });
        Schema::create('horarios_usuarios', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Empresa::class);;
            $table->foreignIdFor(\App\Models\User::class);;
            $table->timestamps();

            $table->unique(['empresa_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
        Schema::dropIfExists('horarios_usuarios');

    }
};
