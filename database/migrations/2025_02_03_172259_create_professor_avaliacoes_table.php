<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
    {
        Schema::create('professor_avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professor_id')->constrained('professores')->onDelete('cascade'); // Relacionamento com professor
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); // Aluno que avaliou
            $table->foreignId('agendamento_id')->constrained('agendamentos')->onDelete('cascade'); // Relacionamento com a aula agendada
            $table->tinyInteger('nota')->comment('Avaliação de 1 a 5 estrelas');
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('professor_avaliacoes');
    }
};
