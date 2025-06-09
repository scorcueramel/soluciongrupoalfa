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
        Schema::create('parentescos_personas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->foreignId('tipo_parentesco_id')->constrained('tipos_parentescos');
            $table->string('nombres_apellidos',200);
            $table->integer('edad');
            $table->string('ocupacion');
            $table->boolean('mismo_inmueble');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parentescos_personas');
    }
};
