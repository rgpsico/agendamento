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
        Schema::table('alunos_enderecos', function (Blueprint $table) {
            // Alterar o tipo de aluno_id para BIGINT UNSIGNED
            $table->bigInteger('aluno_id')->unsigned()->change();
            // Adicionar a chave estrangeira
            $table->foreign('aluno_id')
                  ->references('id')
                  ->on('alunos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alunos_enderecos', function (Blueprint $table) {
            $table->dropForeign(['aluno_id']);
            $table->integer('aluno_id')->change(); // Reverter para INT
        });

    }

};