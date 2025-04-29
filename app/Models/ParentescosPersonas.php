<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $hidden = [
      'created_at',
      'updated_at',
    ];
}
