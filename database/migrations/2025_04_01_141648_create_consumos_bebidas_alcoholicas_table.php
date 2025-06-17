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
        Schema::create('consumos_bebidas_alcoholicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->string('frecuencia_consumo')->nullable();
            $table->text('bebidas_consume')->nullable();
            $table->boolean('tratamiento_alcoholismo')->default(false);
            $table->boolean('trabajo_ebrio')->default(false);
            $table->text('explicacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumos_bebidas_alcoholicas');
    }
};
