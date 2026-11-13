<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fila_atendimentos', function (Blueprint $table) {
            // Quando o barbeiro chamou o cliente ("é a sua vez"); o cliente vê o aviso na tela da fila.
            $table->timestamp('chamado_em')->nullable()->after('chegou_em');
        });
    }

    public function down(): void
    {
        Schema::table('fila_atendimentos', function (Blueprint $table) {
            $table->dropColumn('chamado_em');
        });
    }
};
