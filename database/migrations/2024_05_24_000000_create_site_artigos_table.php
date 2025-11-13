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
        Schema::create('site_artigos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('empresa_site')->cascadeOnDelete();
            $table->string('titulo');
            $table->string('slug');
            $table->string('resumo', 500)->nullable();
            $table->longText('conteudo');
            $table->string('imagem_capa')->nullable();
            $table->enum('status', ['rascunho', 'publicado'])->default('rascunho');
            $table->timestamp('publicado_em')->nullable();
            $table->timestamps();

            $table->unique(['site_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_artigos');
    }
};
