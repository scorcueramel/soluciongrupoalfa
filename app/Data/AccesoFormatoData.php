<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class AccesoFormatoData extends Data{
  public function __construct(
    public string $numero_documento,
    public bool $acceder_formato,
  ){}
}
