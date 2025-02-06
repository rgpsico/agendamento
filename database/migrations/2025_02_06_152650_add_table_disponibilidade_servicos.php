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
        Schema::create('disponibilidade_servicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servico_id')->constrained('servicos')->onDelete('cascade'); // Vinculado ao serviço
            $table->date('data'); // Data específica da disponibilidade
            $table->integer('vagas_totais'); // Quantidade total de vagas para esse dia
            $table->integer('vagas_reservadas')->default(0); // Vagas já reservadas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilidade_servicos');
    }
};
