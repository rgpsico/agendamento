<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MigrateRolesSeeder extends Seeder
{
    public function run()
    {
        $usuarios = Usuario::all();

        foreach ($usuarios as $usuario) {
            $roleName = $usuario->tipo_usuario;
            $isAdmin = $usuario->isAdmin;

            // Criar ou buscar o papel
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Associar o usuário ao papel
            $usuario->assignRole($role);

            // Se for admin, adicionar o papel Admin
            if ($isAdmin) {
                $adminRole = Role::firstOrCreate(['name' => 'Admin']);
                $usuario->assignRole($adminRole);
            }
        }

        // Remover colunas desnecessárias após migrar os dados
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['tipo_usuario', 'isAdmin']);
        });
    }
}