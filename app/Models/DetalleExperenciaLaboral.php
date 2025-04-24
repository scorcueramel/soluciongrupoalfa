<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleExperenciaLaboral extends Model
{
  use HasFactory;

  protected $fillable = [
    'persona_id',
    'recibio_amonestaciones',
    'solicitud_renuncia',
    'explicacion',
  ];
}
