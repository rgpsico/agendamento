<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automacao_sequencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);

            // O que dispara esta sequência automaticamente
            // 'manual'          => só via botão no CRM
            // 'pipeline_status' => ao mover lead para determinado status
            // 'novo_lead'       => ao criar qualquer novo lead
            $table->enum('gatilho', ['manual', 'pipeline_status', 'novo_lead'])->default('manual');
            $table->string('gatilho_valor')->nullable(); // ex: 'em_contato', 'aula_experimental'

            $table->timestamps();

            $table->index(['tenant_id', 'ativo']);
            $table->index(['gatilho', 'gatilho_valor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automacao_sequencias');
    }
};
