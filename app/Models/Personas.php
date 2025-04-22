<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nacionalidad_id',
        'tipo_documento_id',
        'estado_civil_id',
        'genero_id',
        'distrito_id',
        'tipo_vivienda_id',
        'foto',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'numero_documento',
        'fecha_nacimiento',
        'lugar_nacimiento',
        'dirección',
        'otro_tipo_vivienda',
        'telefono',
        'email',
        'brevete',
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
      'deleted_at',
    ];
}
