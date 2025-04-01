<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentescosPersonas extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
        'tipo_parentesco_id',
        'nombres_apellidos',
        'edad',
        'ocupacion',
        'mismo_inmueble',
    ];
}
