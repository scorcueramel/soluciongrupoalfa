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
        Schema::create('implicaciones_drogas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->boolean('marihuana');
            $table->boolean('pbc');
            $table->boolean('cocaina');
            $table->boolean('heroina');
            $table->boolean('lsd');
            $table->boolean('extasis');
            $table->string('ultimo_consumo');
            $table->integer('tiempo_transcurrido');
            $table->boolean('familiar_consumidor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implicaciones_drogas');
    }
};
