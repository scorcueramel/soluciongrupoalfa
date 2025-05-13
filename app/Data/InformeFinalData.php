<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class InformeFinalData extends Data
{
  public function __construct(
    public int $usuario_id,
    public int $persona_id,
    public ?string $drogas_ilegales,
    public ?string $antecedentes,
    public ?string $vinculos,
    public ?string $planes_infiltracion,
    public ?string $proyeccion_tiempo_empresa,
    public ?array $preguntas_relevantes,
    public ?string $conclusion,
  )
  {
  }
}
