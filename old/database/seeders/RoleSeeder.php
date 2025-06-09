<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = Role::create([
            'name'          => 'superadmin'
        ]);
        $superadmin->givePermissionTo([
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
        ]);
        $admin = Role::create([
            'name'          => 'admin'
        ]);
        $admin->givePermissionTo([
            'delete user',
            'update user',
            'read user',
            'create user',
            'read role',
            'read permission',
        ]);
        $operator = Role::create([
            'name'          => 'operator'
        ]);

        $operator->givePermissionTo([
            'read user',
            'create user',
            'read role',
            'read permission',
        ]);
    }
}
