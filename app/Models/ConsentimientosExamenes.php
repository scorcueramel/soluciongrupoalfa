<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsentimientosExamenes extends Model
{
  use HasFactory;

  protected $fillable = [
    'persona_id',
    'fecha_formato',
    'firma',
    'ciudad'
  ];
}
