<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automacao_etapas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sequencia_id')->constrained('automacao_sequencias')->cascadeOnDelete();

            $table->unsignedTinyInteger('ordem')->default(1);

            // Canal de envio
            $table->enum('canal', ['whatsapp', 'email', 'ambos'])->default('whatsapp');

            // Delay após a etapa anterior (ou após o gatilho para a 1ª etapa)
            $table->unsignedSmallInteger('delay_dias')->default(0);
            $table->unsignedSmallInteger('delay_horas')->default(0);

            // Tipo de conteúdo da mensagem
            // 'ia'       => DeepSeek gera mensagem personalizada automaticamente
            // 'template' => usa o texto abaixo com placeholders {nome}, {interesse}, {bairro}
            $table->enum('tipo_mensagem', ['ia', 'template'])->default('ia');

            // Contexto/instruções para a IA (usado quando tipo_mensagem='ia')
            // Ex: "Ofereça uma aula experimental gratuita de pilates focando nos benefícios para a coluna"
            $table->text('instrucao_ia')->nullable();

            // Texto fixo (usado quando tipo_mensagem='template')
            // Suporta: {nome}, {primeiro_nome}, {interesse}, {bairro}, {empresa}
            $table->text('template_mensagem')->nullable();

            // Assunto do e-mail (usado quando canal='email' ou 'ambos')
            $table->string('assunto_email')->nullable();

            $table->timestamps();

            $table->index(['sequencia_id', 'ordem']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automacao_etapas');
    }
};
