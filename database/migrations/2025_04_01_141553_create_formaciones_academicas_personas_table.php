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
        Schema::create('formaciones_academicas_personas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->foreignId('grado_instruccion_id')->constrained('grados_instrucciones');
            $table->string('centro_estudio',100);
            $table->string('especialidad_facultad',100);
            $table->date('fecha_inicio');
            $table->date('fecha_termino')->nullable();
            $table->boolean('situacion')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formaciones_academicas_personas');
    }
};
