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
        Schema::create('personas_informaciones_financieras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained('personas');
            $table->boolean('tiene_prestamo')->default(false);
            $table->double('monto_prestamo',10,2)->nullable();
            $table->string('entidad_bancaria_prestamo_id')->nullable();
            $table->double('cuota_mensual_prestamo',10,2)->nullable();
            $table->boolean('otro_ingreso')->default(false);
            $table->double('monto_ingreso',10,2)->nullable();
            $table->string('origen_ingreso',100)->nullable();
            $table->boolean('tiene_propiedades')->default(false);
            $table->string('detalle_propiedades',150)->nullable();
            $table->boolean('reportado_centrar_riesgos')->default(false);
            $table->string('entidad_bancaria_reporto_id')->nullable(); //almacena el id de entidades financieras para futuras consultas
            $table->text('motivo_reportado')->nullable();
            $table->string('tiempo_mora',50)->nullable();
            $table->double('monto_deuda',10,2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas_informaciones_financieras');
    }
};
