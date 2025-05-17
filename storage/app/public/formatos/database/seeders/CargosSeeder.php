<?php

namespace public\storage\formatos\database\seeders;

use App\Models\Cargos;
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

      Cargos::create([
        'cargo' => $cargo,
        'codigo' => 'CR-' . $contador,
      ]);

      $contador++;
    }
  }
}
