<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nicho_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('nicho')->unique();          // surf, pilates, boxe...
            $table->string('nome');                      // Surf Gestão, Pilates Gestão...
            $table->string('dominio')->nullable();       // surfgestao.com.br
            $table->string('dominio_www')->nullable();   // www.surfgestao.com.br
            $table->string('emoji')->nullable();         // 🏄
            $table->string('cor_primaria')->default('#6a11cb');
            $table->string('cor_secundaria')->default('#2575fc');
            $table->string('logo')->nullable();          // storage/nicho/surf/logo.png
            $table->string('login_imagem')->nullable();  // storage/nicho/surf/login.jpg
            $table->string('registro_imagem')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nicho_configuracoes');
    }
};
