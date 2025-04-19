<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccesoFormatos extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'documento_persona',
    'acceso_formato',
    'codigo_poligrafista',
    'numero_evaluaciones',
    'fecha_examen',
  ];
}
