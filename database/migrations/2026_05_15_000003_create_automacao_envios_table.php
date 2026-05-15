<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automacao_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sequencia_id')->constrained('automacao_sequencias')->cascadeOnDelete();
            $table->foreignId('etapa_id')->constrained('automacao_etapas')->cascadeOnDelete();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->unsignedBigInteger('tenant_id');

            $table->enum('canal', ['whatsapp', 'email']);
            $table->enum('status', ['pendente', 'enviado', 'falhou', 'cancelado'])->default('pendente');

            // Mensagem real enviada (depois de substituir placeholders / gerada pela IA)
            $table->text('mensagem_enviada')->nullable();

            $table->timestamp('agendado_para');
            $table->timestamp('enviado_em')->nullable();
            $table->text('erro')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['lead_id', 'status']);
            $table->index(['agendado_para', 'status']);

            // Evita duplicidade: mesma etapa para o mesmo lead e canal só pode existir uma vez
            $table->unique(['etapa_id', 'lead_id', 'canal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automacao_envios');
    }
};
