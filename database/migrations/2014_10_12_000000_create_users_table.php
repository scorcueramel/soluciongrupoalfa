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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_documento')->constrained('tipos_documentos');
            $table->string('name',50);
            $table->string('apellido_paterno',50);
            $table->string('apellido_materno',50);
            $table->string('codigo_poligrafista',20)->nullable();
            $table->integer('conteo_evaluaciones')->default(0)->nullable();
            $table->string('numero_documento',20);
            $table->string('telefono',20);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
