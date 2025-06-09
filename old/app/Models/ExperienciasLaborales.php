<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienciasLaborales extends Model
{
  use HasFactory;

  protected $fillable = [
    'persona_id',
    'empresa',
    'fecha_ingreso',
    'fecha_salida',
    'sueldo_percibido',
    'cargo_desempenado',
    'motivo_salida',
    'recibio_amonestaciones',
    'solicitud_renuncia',
    'explicacion',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];
}
