<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_templates')->updateOrInsert(
            ['slug' => 'barbearia'],
            [
                'titulo' => 'Barbearia',
                'descricao' => 'Site de barbearia: barbeiros, tabela de preços, horários e fila de check-in ao vivo (só mostra dados reais da empresa).',
                'path_view' => 'site.barbearia',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('site_templates')->where('slug', 'barbearia')->delete();
    }
};
