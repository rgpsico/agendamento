<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Usuario; // ajuste se seu model de usuário tiver outro namespace


class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * 1. Criar permissões padrão
         */
        $permissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            'settings.view',
            'settings.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        /**
         * 2. Criar papéis padrão
         */
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        /**
         * 3. Atribuir permissões a cada papel
         */
        $admin->syncPermissions(Permission::all());

        $manager->syncPermissions([
            'users.view',
            'users.edit',
            'roles.view',
            'settings.view',
        ]);

        $user->syncPermissions([
            'settings.view',
        ]);

        /**
         * 4. Criar usuários padrões e associar papéis
         */
        // Usuário admin
       // Usuário admin
$adminUser = Usuario::firstOrCreate(
    ['email' => 'admin@sistema.com'],
    [
        'nome' => 'Administrador',
        'password' => bcrypt('123456'),
        'tipo_usuario' => 'Professor',
    ]
);
$adminUser->assignRole($admin);

// Usuário gerente
$managerUser = Usuario::firstOrCreate(
    ['email' => 'manager@sistema.com'],
    [
        'nome' => 'Gerente',
        'password' => bcrypt('123456'),
        'tipo_usuario' => 'Professor',
    ]
);
$managerUser->assignRole($manager);

// Usuário normal
$basicUser = Usuario::firstOrCreate(
    ['email' => 'user@sistema.com'],
    [
        'nome' => 'Usuário',
        'password' => bcrypt('123456'),
        'tipo_usuario' => 'Professor',
    ]
);
$basicUser->assignRole($user);


    }
}
