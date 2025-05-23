<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformesFinales extends Model
{
  use HasFactory;

  protected $fillable = [
    'usuario_id',
    'persona_id',
    'drogas_ilegales',
    'antecedentes',
    'vinculos',
    'planes_infiltracion',
    'proyeccion_tiempo_empresa',
    'preguntas_relevantes',
    'conclusion',
    'porcentaje_evaluacion',
  ];
}
