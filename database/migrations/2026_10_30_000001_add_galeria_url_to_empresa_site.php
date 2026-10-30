<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Link externo da galeria completa (ex.: MediaHub); o site de passeios mostra o botão "Ver galeria completa". */
    public function up(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->string('galeria_url', 500)->nullable()->after('whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->dropColumn('galeria_url');
        });
    }
};
