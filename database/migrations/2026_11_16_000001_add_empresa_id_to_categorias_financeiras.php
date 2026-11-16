<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * As categorias do financeiro eram globais: uma empresa via (e editava) as categorias das outras.
     * Agora cada categoria pertence a uma empresa (nulo = padrão visível para todas).
     */
    public function up(): void
    {
        foreach (['financeiro_categorias', 'despesas_categorias'] as $tabela) {
            Schema::table($tabela, function (Blueprint $table) {
                $table->unsignedBigInteger('empresa_id')->nullable()->after('id')->index();
            });
        }

        // Categoria de receita usada por um serviço fica com a empresa desse serviço.
        DB::statement('
            UPDATE financeiro_categorias c
            JOIN (SELECT categoria_id, MIN(empresa_id) AS empresa_id FROM servicos
                  WHERE categoria_id IS NOT NULL GROUP BY categoria_id) s ON s.categoria_id = c.id
            SET c.empresa_id = s.empresa_id
            WHERE c.empresa_id IS NULL
        ');

        // Senão, a empresa dos lançamentos que usam a categoria.
        foreach (['receitas', 'receitas_recorrentes', 'despesas_recorrentes'] as $origem) {
            DB::statement("
                UPDATE financeiro_categorias c
                JOIN (SELECT categoria_id, MIN(empresa_id) AS empresa_id FROM {$origem}
                      WHERE categoria_id IS NOT NULL GROUP BY categoria_id) l ON l.categoria_id = c.id
                SET c.empresa_id = l.empresa_id
                WHERE c.empresa_id IS NULL
            ");
        }

        DB::statement('
            UPDATE despesas_categorias c
            JOIN (SELECT categoria_id, MIN(empresa_id) AS empresa_id FROM despesas
                  WHERE categoria_id IS NOT NULL GROUP BY categoria_id) l ON l.categoria_id = c.id
            SET c.empresa_id = l.empresa_id
            WHERE c.empresa_id IS NULL
        ');
    }

    public function down(): void
    {
        foreach (['financeiro_categorias', 'despesas_categorias'] as $tabela) {
            Schema::table($tabela, function (Blueprint $table) {
                $table->dropIndex(['empresa_id']);
                $table->dropColumn('empresa_id');
            });
        }
    }
};
