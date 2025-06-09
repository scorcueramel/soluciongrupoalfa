<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CargoData extends Data
{
  public function __construct(
    public ?int $id,
    public string $cargo,
  )
  {
  }
}
