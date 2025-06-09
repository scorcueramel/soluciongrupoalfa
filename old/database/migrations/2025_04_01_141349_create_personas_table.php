<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('personas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('nacionalidad_id')->constrained('nacionalidades');
      $table->foreignId('tipo_documento_id')->constrained('tipos_documentos');
      $table->foreignId('estado_civil_id')->constrained('estados_civiles');
      $table->foreignId('genero_id')->constrained('generos');
      $table->foreignId('distrito_id')->constrained('distritos');
      $table->foreignId('tipo_vivienda_id')->constrained('tipos_viviendas');
      $table->text('foto')->nullable();
      $table->string('nombres', 50);
      $table->string('apellido_paterno', 100);
      $table->string('apellido_materno', 100);
      $table->string('numero_documento', 20);
      $table->date('fecha_nacimiento');
      $table->string('lugar_nacimiento', 100);
      $table->string('direccion', 150);
      $table->string('otro_tipo_vivienda', 150)->nullable();
      $table->string('telefono', 50);
      $table->string('email', 100);
      $table->string('brevete', 20)->nullable();
      $table->boolean('informe_final')->default(false);
      $table->softDeletes();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('personas');
  }
};
