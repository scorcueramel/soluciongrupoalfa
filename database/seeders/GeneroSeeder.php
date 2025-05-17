<?php

namespace Database\Seeders;

use App\Models\Generos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contador = 1;

        $generos = [
          'Masculino',
          'Femenino',
          'Otro'
        ];

        foreach ($generos as $genero) {
          Generos::create([
            'genero' => $genero,
            'codigo' => "GN-".$contador,
          ]);
          $contador++;
        }
    }
}
