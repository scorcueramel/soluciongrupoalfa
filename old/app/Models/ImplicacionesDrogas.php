<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImplicacionesDrogas extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
        'marihuana',
        'pbc',
        'cocaina',
        'heroina',
        'lsd',
        'extasis',
        'tiempo_transcurrido',
        'ultimo_consumo',
        'familiar_consumidor',
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
    ];
}
