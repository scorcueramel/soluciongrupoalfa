<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AccesoFormatos;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();

    $this->call([
      PermissionSeeder::class,
      RoleSeeder::class,
      CargosSeeder::class,
      NacionalidadSeeder::class,
      DistritoSeeder::class,
      TiposPersonasSeeder::class,
      GradosInstruccionesSeeder::class,
      EntidadesBancariasSeeder::class,
      EmpresaSeeder::class,
      TiposDocumentosSeeder::class,
      GeneroSeeder::class,
      EstadoCivilSeeder::class,
      UserSeeder::class,
      TipoViviendaSeeder::class,
    ]);

    AccesoFormatos::create([
      'documento_persona' => '48398529',
      'acceso_formato' => true,
      'codigo_poligrafista' => 'SPA-',
      'numero_evaluaciones' => '001',
      'poligrafista_id' => 1,
      'fecha_examen' => date('Y-m-d'),
    ]);
  }
}
