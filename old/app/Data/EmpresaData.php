<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class EmpresaData extends Data{
  public function __construct(
    public ?int $id,
    public string $nombreComercial,
    public string $razonSocial,
  ){}
}
