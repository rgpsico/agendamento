<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('site_templates')->updateOrInsert(
            ['slug' => 'passeios'],
            [
                'titulo' => 'Passeios',
                'descricao' => 'Site enxuto para passeios e turismo (só mostra dados reais da empresa)',
                'path_view' => 'site.passeios',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('site_templates')->where('slug', 'passeios')->delete();
    }
};
