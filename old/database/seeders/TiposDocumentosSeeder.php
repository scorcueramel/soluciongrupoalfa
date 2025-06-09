<?php

namespace Database\Seeders;

use App\Models\TiposDocumentos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TiposDocumentosSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $contador = 0;

    $tiposDocumentos = [
      'DNI',
      'Pasaporte',
      'Cédula',
      'PTP',
      'CE',
      'CR',
    ];

    foreach ($tiposDocumentos as $tpd) {
      $contador++;
      TiposDocumentos::create([
        'tipo_documento' => $tpd,
        'codigo' => 'TPD-'.$contador,
      ]);
    }
  }
}
