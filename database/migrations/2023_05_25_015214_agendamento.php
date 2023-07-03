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
            $table->id();
            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('modalidade_id');
            $table->unsignedBigInteger('professor_id');
            $table->decimal('valor_aula', 8, 2);

            $table->string('status')->default('Espera'); //espera , feita , cancelada , adiada
            $table->timestamp('data_da_aula');

            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->foreign('modalidade_id')->references('id')->on('modalidade');
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
