<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class AccesoFormatoData extends Data{
  public function __construct(
    public ?int $id,
    public string $numero_documento,
    public bool $acceder_formato,
  ){}
}
