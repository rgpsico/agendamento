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
            $table->timestamp('data_agendamento');
            $table->timestamps();

            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->foreign('aula_id')->references('id')->on('aulas');
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
