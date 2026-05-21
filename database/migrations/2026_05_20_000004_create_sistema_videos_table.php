<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sistema_videos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['youtube', 'vimeo', 'upload'])->default('youtube');
            $table->string('url', 500)->nullable();          // YouTube/Vimeo URL
            $table->string('video_id', 100)->nullable();     // ID extraído (YouTube/Vimeo)
            $table->string('arquivo', 500)->nullable();      // path do arquivo enviado
            $table->string('thumbnail', 500)->nullable();    // thumbnail customizada
            $table->string('nicho', 50)->nullable()->index();
            $table->string('categoria', 100)->nullable();    // ex: "Funcionalidades", "Depoimentos"
            $table->integer('duracao_segundos')->nullable();
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sistema_videos');
    }
};
