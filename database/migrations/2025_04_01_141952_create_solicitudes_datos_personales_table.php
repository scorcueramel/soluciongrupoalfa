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
        Schema::create('solicitudes_datos_personales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('cargo_id')->constrained('cargos');
            $table->foreignId('usuario_id')->constrained('users');
            $table->date('fecha_solicitud');
            $table->text('firma');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_datos_personales');
    }
};
