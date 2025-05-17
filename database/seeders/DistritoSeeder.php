<?php

namespace Database\Seeders;

use App\Models\Distritos;
use Illuminate\Database\Seeder;

class DistritoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $distritos = [
      "Ancon",
      "Ate",
      "Barranco",
      "Breña",
      "Carabayllo",
      "Cercado de Lima",
      "Chaclacayo",
      "Chorrillos",
      "Cieneguilla",
      "Comas",
      "El Agustino",
      "Independencia",
      "Jesus Maria",
      "La Molina",
      "La Victoria",
      "Lince",
      "Los Olivos",
      "Lurigancho",
      "Lurin",
      "Magdalena del Mar",
      "Miraflores",
      "Pachacamac",
      "Pucusana",
      "Pueblo Libre",
      "Puente Piedra",
      "Punta Hermosa",
      "Punta Negra",
      "Rimac",
      "San Bartolo",
      "San Borja",
      "San Isidro",
      "San Juan de Lurigancho",
      "San Juan de Miraflores",
      "San Luis",
      "San Martin de Porres",
      "San Miguel",
      "Santa Anita",
      "Santa Maria del Mar",
      "Santa Rosa",
      "Santiago de Surco",
      "Surquillo",
      "Villa El Salvador",
      "Villa Maria del Triunfo"
    ];
    $contador = 1;

    for ($i = 0; $i < count($distritos); $i++) {
      Distritos::create([
        'distrito' => $distritos[$i],
        'codigo' => "D-$contador",
      ]);

      $contador++;
    }
  }
}
