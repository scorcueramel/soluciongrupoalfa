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
        Schema::create('personas_margen_leyes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->boolean('pandilleros');
            $table->boolean('sicarios');
            $table->boolean('asaltantes');
            $table->boolean('traficantes_drogas');
            $table->boolean('estafadores');
            $table->boolean('terroristas');
            $table->boolean('secuestradores');
            $table->boolean('extorsionadores');
            $table->boolean('otros')->nullable();
            $table->boolean('familiares_sentenciados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas_margen_leyes');
    }
};
