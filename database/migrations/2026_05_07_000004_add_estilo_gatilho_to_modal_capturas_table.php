<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modal_capturas', function (Blueprint $table) {
            // Estilo
            $table->string('tamanho')->default('medio')->after('cor_primaria');         // pequeno|medio|grande
            $table->string('cor_texto', 7)->default('#333333')->after('tamanho');
            $table->string('cor_fundo', 7)->default('#ffffff')->after('cor_texto');
            $table->unsignedTinyInteger('bordas')->default(12)->after('cor_fundo');     // 0-24px
            $table->string('imagem_url')->nullable()->after('bordas');                  // URL da imagem do topo
            $table->string('posicao')->default('centro')->after('imagem_url');          // centro|direito|esquerdo

            // Gatilho
            $table->string('gatilho')->default('imediato')->after('posicao');           // imediato|delay|scroll|elemento|saida
            $table->string('gatilho_valor')->nullable()->after('gatilho');              // segundos, %, seletor CSS

            // Comportamento
            $table->boolean('mostrar_uma_vez')->default(true)->after('gatilho_valor');  // localStorage
        });
    }

    public function down(): void
    {
        Schema::table('modal_capturas', function (Blueprint $table) {
            $table->dropColumn([
                'tamanho', 'cor_texto', 'cor_fundo', 'bordas',
                'imagem_url', 'posicao', 'gatilho', 'gatilho_valor', 'mostrar_uma_vez',
            ]);
        });
    }
};
