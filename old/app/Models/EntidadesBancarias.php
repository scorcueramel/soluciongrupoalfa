<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadesBancarias extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_entidad',
        'codigo',
    ];
}
