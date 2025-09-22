<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aluno_planos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('plano_id')->constrained('planos_alunos')->onDelete('cascade');
            $table->date('data_inicio')->nullable(); // Data de início do plano
            $table->date('data_fim')->nullable();    // Data de término do plano
            $table->enum('status', ['ativo', 'inativo', 'cancelado'])->default('ativo'); // Status do plano
            $table->decimal('valor_pago', 10, 2)->nullable(); // Valor pago pelo aluno
            $table->string('forma_pagamento')->nullable();   // Ex: cartão, pix, boleto
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aluno_planos');
    }
};
