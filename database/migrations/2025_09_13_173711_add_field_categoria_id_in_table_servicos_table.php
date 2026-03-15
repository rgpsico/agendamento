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
        Schema::table('servicos', function (Blueprint $table) {
            // Adiciona a coluna categoria_id como nullable e unsigned bigInteger
            $table->unsignedBigInteger('categoria_id')->nullable()->after('tipo_agendamento');

            // Define a foreign key para a tabela financeiro_categorias
            $table->foreign('categoria_id')
                ->references('id')
                ->on('financeiro_categorias')
                ->onDelete('set null'); // se a categoria for deletada, define como null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']); // remove a foreign key
            $table->dropColumn('categoria_id');   // remove a coluna
        });
    }
};
