<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // Crear roles
        $roleAdmin = Role::create(['name' => 'administrador']);
        $roleCliente = Role::create(['name' => 'cliente']);

        // Asignar permisos a roles
        $roleAdmin->givePermissionTo(['view users', 'edit users', 'delete users']);
        $roleCliente->givePermissionTo('view users');
    }
}