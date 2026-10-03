<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_qualificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('token', 40)->unique();
            $table->string('status', 20)->default('pendente'); // pendente | respondido

            // Respostas do wizard
            $table->string('nome')->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('cidade')->nullable();
            $table->string('nicho', 30)->nullable();           // turismo, beleza, surf, bodyboard, pilates, outro
            $table->string('nicho_outro')->nullable();
            $table->string('tem_google_meu_negocio', 10)->nullable(); // sim | nao | nao_sei
            $table->string('ja_fez_campanha', 20)->nullable();        // nunca | sozinho | agencia | sempre
            $table->string('nivel_digital', 20)->nullable();          // basico | intermediario | avancado
            $table->text('observacao')->nullable();

            $table->timestamp('respondido_em')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('nicho');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_qualificacoes');
    }
};
