<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormacionesAcademicasPersonas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'persona_id',
        'grado_instruccion_id',
        'centro_estudio',
        'especialidad_facultad',
        'fecha_inicio',
        'fecha_termino',
        'situacion',
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
    ];
}
