<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generos extends Model
{
    use HasFactory;

    protected $fillable = [
        'genero',
        'codigo',
    ];

    public function personas():HasMany{
      return $this->hasMany(Personas::class);
    }
}
