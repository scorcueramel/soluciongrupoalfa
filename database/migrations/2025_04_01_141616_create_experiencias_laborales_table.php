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
        Schema::create('experiencias_laborales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->string('empresa')->nullable();
            $table->string('fecha_ingreso')->nullable();
            $table->string('fecha_salida')->nullable();
            $table->double('sueldo_percibido',10,2)->nullable();
            $table->string('cargo_desempenado',100)->nullable();
            $table->string('motivo_salida',100)->nullable();
            $table->boolean('recibio_amonestaciones')->default(false);
            $table->boolean('solicitud_renuncia')->default(false);
            $table->text('explicacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiencias_laborales');
    }
};
