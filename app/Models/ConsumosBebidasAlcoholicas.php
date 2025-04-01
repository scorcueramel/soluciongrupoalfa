<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumosBebidasAlcoholicas extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
        'frecuencia_consumo',
        'bebidas_consume',
        'tratamiento_alcoholismo',
        'trabajo_ebrio',
        'explicacion',
    ];
}
