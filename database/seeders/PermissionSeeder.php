<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos explícitos
        $permissions = [
            // Usuarios
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            // Barberos
            'ver barberos',
            'crear barberos',
            'editar barberos',
            'eliminar barberos',

            // Servicios
            'ver servicios',
            'crear servicios',
            'editar servicios',
            'eliminar servicios',

            // Citas (admin)
            'ver todas las citas',
            'editar todas las citas',
            'eliminar todas las citas',

            // Citas (barbero)
            'ver citas asignadas',
            'editar citas asignadas',
            'eliminar citas asignadas',

            // Citas (usuario)
            'crear citas propias',
            'ver citas propias',
            'cancelar citas propias',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $barbero = Role::firstOrCreate(['name' => 'barbero']);
        $usuario = Role::firstOrCreate(['name' => 'usuario']);

        // Asignar permisos a admin (todos)
        $admin->syncPermissions(Permission::all());

        // Asignar permisos a barbero
        $barbero->syncPermissions([
            'ver citas asignadas',
            'editar citas asignadas',
            'eliminar citas asignadas',
            'ver servicios',
        ]);

        // Asignar permisos a usuario
        $usuario->syncPermissions([
            'crear citas propias',
            'ver citas propias',
            'cancelar citas propias',
        ]);
    }
}
