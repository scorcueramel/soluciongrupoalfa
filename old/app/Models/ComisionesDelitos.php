<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComisionesDelitos extends Model
{
  use HasFactory;

  protected $fillable = [
    'persona_id',
    'robo_hurto_fraude',
    'homicidio_involuntario',
    'asalto',
    'danio_fisico_individuo',
    'secuestro',
    'violacion',
    'muerte_lesion_otra_persona',
    'trafico_ilicito_drogas',
    'trafico_armas',
    'otros_delitos',
    'explique_otros',
  ];
}
