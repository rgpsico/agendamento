<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sem domínio: o nicho só passa a valer num endereço quando o dono do sistema definir o domínio no super admin.
        if (! DB::table('nicho_configuracoes')->where('nicho', 'barbearia')->exists()) {
            DB::table('nicho_configuracoes')->insert([
                'nicho' => 'barbearia',
                'nome' => 'Barbearia Gestão',
                'emoji' => '💈',
                'cor_primaria' => '#1f1f1f',
                'cor_secundaria' => '#c9a24b',
                'recursos' => json_encode(['fila_checkin' => true]),
                'termos' => json_encode([
                    'profissional' => 'Barbeiro',
                    'profissionais' => 'Barbeiros',
                    'cliente' => 'Cliente',
                    'clientes' => 'Clientes',
                ], JSON_UNESCAPED_UNICODE),
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! DB::table('modalidade')->where('nicho', 'barbearia')->exists()) {
            DB::table('modalidade')->insert(['nome' => 'Barbearia', 'nicho' => 'barbearia', 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        DB::table('modalidade')->where('nicho', 'barbearia')->delete();
        DB::table('nicho_configuracoes')->where('nicho', 'barbearia')->delete();
    }
};
