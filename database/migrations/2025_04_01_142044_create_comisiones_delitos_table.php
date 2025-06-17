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
        Schema::create('comisiones_delitos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->boolean('robo_hurto_fraude')->default(false);
            $table->boolean('homicidio_involuntario')->default(false);
            $table->boolean('asalto')->default(false);
            $table->boolean('danio_fisico_individuo')->default(false);
            $table->boolean('secuestro')->default(false);
            $table->boolean('violacion')->default(false);
            $table->boolean('muerte_lesion_otra_persona')->default(false);
            $table->boolean('trafico_ilicito_drogas')->default(false);
            $table->boolean('trafico_armas')->default(false);
            $table->boolean('otros_delitos')->default(false);
            $table->text('explique_otros')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comisiones_delitos');
    }
};
