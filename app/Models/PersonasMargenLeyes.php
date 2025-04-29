<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonasMargenLeyes extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_id',
        'pandilleros',
        'sicarios',
        'asaltantes',
        'traficantes_drogas',
        'estafadores',
        'terroristas',
        'secuestradores',
        'extorsionadores',
        'otros',
        'familiares_sentenciados',
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
    ];
}
