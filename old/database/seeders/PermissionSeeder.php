<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    $permisos = [
      'delete user',
      'update user',
      'read user',
      'create user',
      'delete role',
      'update role',
      'read role',
      'create role',
      'delete permission',
      'update permission',
      'read permission',
      'create permission',
      'read evaluado',
      'format evaluado',
      'consent evaluado',
      'report evaluado',
      'final report evaluado',
      'read formato',
      'update formato',
      'create formato',
      'delete formato',
      'read empresa',
      'create empresa',
      'update empresa',
      'delete empresa',
      'update evaluado',
      'create evaluado',
      'update cargo',
      'read cargo',
      'create cargo',
      'delete cargo',
    ];

    foreach ($permisos as $permiso) {
      Permission::create(['name' => $permiso,'guard_name' => 'web']);
    }
  }
}
