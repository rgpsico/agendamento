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

        Schema::create('agendamentos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('aluno_id');
            $table->uuid('aula_id');
            $table->uuid('professor_id');
            $table->timestamp('data_agendamento');

            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->foreign('aula_id')->references('id')->on('aulas');
            $table->foreign('professor_id')->references('id')->on('professores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
