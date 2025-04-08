<?php

namespace Database\Seeders;

use App\Models\TiposParentescos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiposPersonasSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $contador = 0;
    $tiposPersonas = [
      'Conyuge',
      'Hijos',
      'Hermanos',
      'Padre',
      'Madre'
    ];

    foreach ($tiposPersonas as $tipoPersona) {
      TiposParentescos::create([
        'tipo_parentesco' => $tipoPersona,
        'codigo' => "TP-00" . $contador += 1,
      ]);
    }
  }
}
