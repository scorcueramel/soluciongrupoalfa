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
        Schema::create('acceso_formatos', function (Blueprint $table) {
            $table->id();
            $table->string('documento_persona');
            $table->boolean('acceso_formato')->default(false);
            $table->string('codigo_poligrafista',20);
            $table->string('numero_evaluaciones',20)->nullable();
            $table->integer('poligrafista_id');
            $table->date('fecha_examen');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acceso_formatos');
    }
};
