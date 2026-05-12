<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->string('widget_token', 48)->nullable()->unique()->after('prompt');
            $table->boolean('widget_ativo')->default(false)->after('widget_token');
            $table->string('widget_cor', 7)->default('#2a5298')->after('widget_ativo');       // cor do bubble e header
            $table->string('widget_posicao')->default('direito')->after('widget_cor');         // direito|esquerdo
            $table->string('widget_saudacao')->nullable()->after('widget_posicao');            // mensagem inicial
            $table->string('widget_avatar_url')->nullable()->after('widget_saudacao');         // URL do avatar/logo
            $table->string('widget_nome_bot')->nullable()->after('widget_avatar_url');         // nome exibido no header do chat
        });
    }

    public function down(): void
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn([
                'widget_token', 'widget_ativo', 'widget_cor',
                'widget_posicao', 'widget_saudacao', 'widget_avatar_url', 'widget_nome_bot',
            ]);
        });
    }
};
