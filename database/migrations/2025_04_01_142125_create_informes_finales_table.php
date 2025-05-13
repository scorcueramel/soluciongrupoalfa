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
        Schema::create('informes_finales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('persona_id')->constrained('personas');
            $table->text('drogas_ilegales');
            $table->text('antecedentes');
            $table->text('vinculos');
            $table->text('planes_infiltracion');
            $table->text('proyeccion_tiempo_empresa');
            $table->text('preguntas_relevantes');
            $table->text('conclusion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informes_finales');
    }
};
