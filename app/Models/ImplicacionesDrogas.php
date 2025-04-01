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
        'ultimo_consumo',
        'tiempo_transcurrido',
        'familiar_consumidor',
    ];
}
