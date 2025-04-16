<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TiposDocumentos extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_documento',
        'codigo',
    ];

    public function usuarios():HasMany{
      return $this->hasMany(User::class, 'tipodocumento_id');
    }
}
