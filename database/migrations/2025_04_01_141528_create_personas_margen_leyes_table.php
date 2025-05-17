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
            $table->boolean('pandilleros')->default(false);
            $table->boolean('sicarios')->default(false);
            $table->boolean('asaltantes')->default(false);
            $table->boolean('traficantes_drogas')->default(false);
            $table->boolean('estafadores')->default(false);
            $table->boolean('terroristas')->default(false);
            $table->boolean('secuestradores')->default(false);
            $table->boolean('extorsionadores')->default(false);
            $table->boolean('otros')->nullable()->default(false);
            $table->boolean('familiares_sentenciados')->default(false);
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
