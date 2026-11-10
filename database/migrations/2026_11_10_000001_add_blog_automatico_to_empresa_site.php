<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            // O gerador automático de artigos (artigo:gerar) só publica nos sites marcados
            $table->boolean('blog_automatico')->default(false)->after('ativo');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->dropColumn('blog_automatico');
        });
    }
};
