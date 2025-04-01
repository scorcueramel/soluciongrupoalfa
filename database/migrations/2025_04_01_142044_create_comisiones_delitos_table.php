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
            $table->boolean('robo_hurto_fraude');
            $table->boolean('homicidio_involuntario');
            $table->boolean('asalto');
            $table->boolean('danio_fisico_individuo');
            $table->boolean('secuestro');
            $table->boolean('violacion');
            $table->boolean('muerte_lesion_otra_persona');
            $table->boolean('trafico_ilicito_drogas');
            $table->boolean('otros_delitos');
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
