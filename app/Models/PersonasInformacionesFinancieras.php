<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PersonasInformacionesFinancieras extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'persona_id',
        'entidad_bancaria_id',
        'tiene_prestamo',
        'monto_prestamo',
        'cuota_mensual_prestamo',
        'otro_ingreso',
        'monto_ingreso',
        'origen_ingreso',
        'tiene_propiedades',
        'detalle_propiedades',
        'reportado_centrar_riesgos',
        'entidad_reportado',
        'motivo_reportado',
        'tiempo_mora',
        'monto_deuda',
    ];
}
