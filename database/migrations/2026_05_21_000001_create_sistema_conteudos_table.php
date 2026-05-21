<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sistema_conteudos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('nicho', 50)->nullable();
            $table->enum('formato', ['artigo', 'post_instagram', 'post_tiktok', 'legenda_video'])->default('artigo');
            $table->text('topico')->nullable();           // prompt/brief usado para geração
            $table->longText('corpo')->nullable();        // conteúdo gerado (HTML para artigo, texto para post)
            $table->text('legenda')->nullable();          // versão curta/legenda para redes sociais
            $table->string('hashtags', 500)->nullable();
            $table->enum('status', ['rascunho', 'revisado', 'publicado'])->default('rascunho');
            $table->string('publicado_instagram_em')->nullable();
            $table->string('publicado_tiktok_em')->nullable();
            $table->string('imagem_capa')->nullable();   // path storage para upload
            $table->integer('palavras_count')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sistema_conteudos');
    }
};
