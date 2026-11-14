<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fila_atendimentos', function (Blueprint $table) {
            // Recado do barbeiro ("onde você está?") e resposta rápida do cliente; só a última de cada.
            $table->string('mensagem', 160)->nullable()->after('chamado_em');
            $table->timestamp('mensagem_em')->nullable()->after('mensagem');
            $table->string('resposta', 60)->nullable()->after('mensagem_em');
            $table->timestamp('resposta_em')->nullable()->after('resposta');
        });
    }

    public function down(): void
    {
        Schema::table('fila_atendimentos', function (Blueprint $table) {
            $table->dropColumn(['mensagem', 'mensagem_em', 'resposta', 'resposta_em']);
        });
    }
};
