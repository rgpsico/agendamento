<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Receitas criadas por agendamento gravavam o id da tabela "alunos" em receitas.usuario_id
     * (que aponta para "usuarios"). Corrige só as que têm exatamente esse erro:
     * usuario_id igual ao aluno_id do pagamento e diferente do usuário desse aluno.
     */
    public function up(): void
    {
        DB::statement('
            UPDATE receitas r
            JOIN pagamentos p ON p.id = r.pagamento_id
            JOIN alunos a ON a.id = p.aluno_id
            SET r.usuario_id = a.usuario_id
            WHERE r.usuario_id = p.aluno_id
              AND a.usuario_id IS NOT NULL
              AND a.usuario_id <> r.usuario_id
        ');
    }

    public function down(): void
    {
        // Sem volta: o valor antigo era o id errado (de outra tabela).
    }
};
