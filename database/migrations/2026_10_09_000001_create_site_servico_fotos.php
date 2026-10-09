<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fotos extras de cada serviço do site (a capa continua em site_servicos.imagem).
        Schema::create('site_servico_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_servico_id')->constrained('site_servicos')->cascadeOnDelete();
            $table->string('caminho');
            $table->string('credito')->nullable();
            $table->string('credito_url', 500)->nullable();
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();

            $table->index(['site_servico_id', 'ordem']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_servico_fotos');
    }
};
