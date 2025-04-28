<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TiposParentescos extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_parentesco',
        'codigo',
    ];

    public function parentescos(): HasMany{
      return $this->hasMany(ParentescosPersonas::class, 'tipo_parentesco_id', 'id');
    }
}
