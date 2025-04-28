<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadosCiviles extends Model
{
    use HasFactory;

    protected $fillable = [
        'estado_civil',
        'codigo',
    ];

    public function personas():HasMany{
      return $this->hasMany(Personas::class);
    }
}
