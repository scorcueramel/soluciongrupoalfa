<?php

namespace Database\Seeders;

use App\Models\EstadosCiviles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoCivilSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $contador = 0;

    $estadosCiviles = [
      'Soltero',
      'Casado',
      'Viudo',
      'Divorciado',
      'Conviviente',
    ];

    foreach ($estadosCiviles as $estadoCivil) {
      EstadosCiviles::create([
        'estado_civil' => $estadoCivil,
        'codigo' => "EST-00" . $contador+1
      ]);
      $contador++;
    }

  }
}
