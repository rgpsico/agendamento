<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planos_alunos', function (Blueprint $table) {
            $table->id();
            $table->string('nome'); // Nome do plano, ex: Mensal, Trimestral
            $table->text('descricao')->nullable(); // Descrição do plano
            $table->decimal('valor', 10, 2); // Valor do plano
            $table->integer('duracao_dias'); // Duração do plano em dias
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planos_alunos');
    }
};
