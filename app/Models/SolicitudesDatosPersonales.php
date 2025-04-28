<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudesDatosPersonales extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
        'empresa_id',
        'cargo_id',
        'usuario_id',
        'fecha_solicitud',
        'firma',
    ];
}
