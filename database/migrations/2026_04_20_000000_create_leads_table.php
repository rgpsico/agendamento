<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('empresa')->nullable();
            $table->string('origem')->default('manual'); // manual, site, whatsapp, indicacao, redes_sociais
            $table->string('status')->default('novo'); // novo, em_contato, qualificado, proposta_enviada, convertido, perdido
            $table->string('interesse')->nullable();
            $table->text('observacoes')->nullable();
            $table->foreignId('responsavel_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
