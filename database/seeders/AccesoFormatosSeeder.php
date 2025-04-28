<?php

namespace Database\Seeders;

use App\Models\AccesoFormatos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccesoFormatosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i=0; $i<=5000;$i++){
          AccesoFormatos::create([
            'documento_persona'=>Str::random(8),
            'acceso_formato'=>true,
            'codigo_poligrafista'=>'SPA-'.$i,
            'numero_evaluaciones'=>$i,
            'poligrafista_id'=>1,
            'fecha_examen'=>date('Y-m-d'),
          ]);
        }
    }
}
