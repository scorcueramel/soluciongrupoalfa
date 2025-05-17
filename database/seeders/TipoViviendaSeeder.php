<?php

namespace Database\Seeders;

use App\Models\TiposViviendas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoViviendaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $contador = 1;

    $tiposViviendas= [
      'Propia',
      'Alquilada',
      'Padres',
      'Familiar',
      'Otros'
    ];

    foreach ($tiposViviendas as $tipoVivienda) {
      TiposViviendas::create([
        'tipo_vivienda' => $tipoVivienda,
        'codigo' => $contador
      ]);
      $contador++;
    }
  }
}
