<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fila de check-in (barbearia): o cliente chega, avisa pelo celular e espera a vez do barbeiro escolhido.
        Schema::create('fila_atendimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->index();
            $table->unsignedBigInteger('professor_id');
            $table->unsignedBigInteger('aluno_id')->index();
            $table->string('status', 20)->default('aguardando'); // aguardando, atendendo, concluido, desistiu
            $table->timestamp('chegou_em')->useCurrent();
            $table->timestamp('iniciado_em')->nullable();
            $table->timestamp('finalizado_em')->nullable();
            $table->timestamps();

            $table->index(['professor_id', 'status', 'chegou_em']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fila_atendimentos');
    }
};
