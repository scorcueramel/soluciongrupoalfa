<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradosInstrucciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'grado_instruccion',
        'codigo',
    ];
}
