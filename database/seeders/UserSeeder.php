<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $superadmin = User::create([
      'tipodocumento_id' => 1,
      'name' => 'Superadmin',
      'apellido_paterno' => 'superadmin',
      'apellido_materno' => 'superadmin',
      'codigo_poligrafista' => 'SPA-',
      'numero_documento' => '12345678',
      'telefono' => '987654321',
      'email' => 'superadmin@superadmin.com',
      'password' => bcrypt('superadmin'),
      'email_verified_at' => date('Y-m-d H:i')
    ]);
    $superadmin->assignRole('superadmin');

    $admin = User::create([
      'tipodocumento_id' => 1,
      'name' => 'Admin',
      'apellido_paterno' => 'admin',
      'apellido_materno' => 'admin',
      'codigo_poligrafista' => 'ADM-',
      'numero_documento' => '12345678',
      'telefono' => '987654321',
      'email' => 'admin@admin.com',
      'password' => bcrypt('admin'),
      'email_verified_at' => date('Y-m-d H:i')
    ]);
    $admin->assignRole('admin');

    $operator = User::create([
      'tipodocumento_id' => 1,
      'name' => 'Operator',
      'apellido_paterno' => 'operdaor',
      'apellido_materno' => 'operdaor',
      'codigo_poligrafista' => 'OPE-',
      'numero_documento' => '12345678',
      'telefono' => '987654321',
      'email' => 'operator@operator.com',
      'password' => bcrypt('operator'),
      'email_verified_at' => date('Y-m-d H:i')
    ]);
    $operator->assignRole('operator');
  }
}
