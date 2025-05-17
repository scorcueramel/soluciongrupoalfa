<?php

namespace Database\Seeders;

use App\Models\GradosInstrucciones;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradosInstruccionesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $contador = 0;
    $grados = [
      'Estudiante',
      'Primaria',
      'Secundaria',
      'Técnico',
      'Bachiller',
      'Profesional Títulado',
      'Profesional Colegiado',
      'Magiste',
      'Doctor'
    ];

    foreach ($grados as $grado) {
      GradosInstrucciones::create([
        'grado_instruccion' => $grado,
        'codigo' => "GI-".$contador+1
      ]);
      $contador++;
    }
  }
}
