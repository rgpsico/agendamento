<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            // Adiciona a coluna servico_id como chave estrangeira opcional
            $table->unsignedBigInteger('servico_id')->nullable()->after('modalidade_id');

            // Se quiser criar a foreign key:
            $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropForeign(['servico_id']);
            $table->dropColumn('servico_id');
        });
    }
};
