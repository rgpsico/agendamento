<?php

use App\Support\Modulos;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Permissões por área do painel ("modulo.<chave>") que o dono libera para cada funcionário.
     * Funcionários que já existiam ganham o padrão (agenda e clientes) para não perderem o que usam no dia a dia;
     * o restante (financeiro etc.) o dono libera em Usuários.
     */
    public function up(): void
    {
        $ids = [];
        foreach (array_keys(Modulos::AREAS) as $modulo) {
            $nome = Modulos::permissao($modulo);
            $id = DB::table('permissions')->where('name', $nome)->where('guard_name', 'web')->value('id');
            if (! $id) {
                $id = DB::table('permissions')->insertGetId([
                    'name' => $nome, 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            $ids[$modulo] = $id;
        }

        // Funcionários atuais: professores de uma empresa que não são donos de nenhuma.
        $funcionarios = DB::table('professores')
            ->whereNotNull('empresa_id')
            ->whereNotIn('usuario_id', DB::table('empresa')->whereNotNull('user_id')->select('user_id'))
            ->pluck('usuario_id')->unique();

        foreach ($funcionarios as $usuarioId) {
            foreach (Modulos::PADRAO_FUNCIONARIO as $modulo) {
                DB::table('model_has_permissions')->insertOrIgnore([
                    'permission_id' => $ids[$modulo],
                    'model_type'    => \App\Models\Usuario::class,
                    'model_id'      => $usuarioId,
                ]);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $ids = DB::table('permissions')->where('name', 'like', 'modulo.%')->pluck('id');
        DB::table('model_has_permissions')->whereIn('permission_id', $ids)->delete();
        DB::table('permissions')->whereIn('id', $ids)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
