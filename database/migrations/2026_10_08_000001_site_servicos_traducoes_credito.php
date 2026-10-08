<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_servicos', function (Blueprint $table) {
            // {"en":{"t":"titulo","d":"descricao"},"es":{...},"it":{...}} — usado pelo template "passeios"
            $table->json('traducoes')->nullable()->after('imagem');
            // Crédito obrigatório de fotos com licença (ex.: Wikimedia Commons CC BY-SA)
            $table->string('imagem_credito')->nullable()->after('traducoes');
            $table->string('imagem_credito_url', 500)->nullable()->after('imagem_credito');
        });
    }

    public function down(): void
    {
        Schema::table('site_servicos', function (Blueprint $table) {
            $table->dropColumn(['traducoes', 'imagem_credito', 'imagem_credito_url']);
        });
    }
};
