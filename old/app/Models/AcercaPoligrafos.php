<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcercaPoligrafos extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'persona_id',
        'paso_antes_examen',
        'explique_paso_antes',
        'empresa',
        'fecha',
        'motivo',
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
    ];
}
