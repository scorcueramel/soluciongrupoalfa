<?php

namespace Database\Seeders;

use App\Models\Cargos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CargosSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $contador = 1;
    $cargos = [
      'Gerente General',
      'Gerente de Tienda',
      'Administrador',
      'Supervisor Comercial',
      'Supervisor de Logistica',
      'Asistente Administrativo',
      'Jefe de Contabilidad',
      'Jefe de Ventas',
      'Jefe de Logistica',
      'Auxiliar de Contable',
      'Vendedores',
      'Almacén',
      'Seguridad',
      'Caja',
      'Operarios',
      'Operarios de Almacen',
      'Personal CCTV',
      'Asesores de Call Center',
      'Reponedores',
      'Personal de Seguridad',
    ];

    foreach ($cargos as $cargo) {

      if($contador < 10){
        Cargos::create([
          'cargo' => $cargo,
          'codigo' => 'CR-00'.$contador,
        ]);
      }
      if($contador >= 10 && $contador < 100){
        Cargos::create([
          'cargo' => $cargo,
          'codigo' => 'CR-0'.$contador,
        ]);
      }

      if($contador > 100){
        Cargos::create([
          'cargo' => $cargo,
          'codigo' => 'CR-'.$contador,
        ]);
      }
      $contador++;
    }
  }
}
