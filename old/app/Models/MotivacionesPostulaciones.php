<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivacionesPostulaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
        'causar_danio',
        'beneficio_ilegal',
        'familiares_en_empresa',
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
    ];
}
