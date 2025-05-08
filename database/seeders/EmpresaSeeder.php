<?php

namespace Database\Seeders;

use App\Models\Empresas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $contador = 0;

    $empresas = [
      "Credicorp Ltd.",
      "Petroleos del Perú S.A.",
      "InRetail Perú Corp.",
      "Banco de Crédito del Perú S.A.",
      "Alicorp S.A.A.",
      "Sociedad Minera Cerro Verde S.A.A.",
      "Southern Copper Corp. Perú",
      "Minsur S.A.",
      "Intercorp Financial Services Inc.",
      "Banco BBVA Perú S.A.",
    ];

    foreach ($empresas as $empresa) {
      $contador++;
      Empresas::create([
        'nombre_comercial' => $empresa,
        'razon_social' => $empresa,
        'codigo' => 'CP-'.$contador,
      ]);
    }
  }
}
