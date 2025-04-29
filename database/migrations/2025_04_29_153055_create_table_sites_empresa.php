<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa_site', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('titulo')->nullable();
            $table->string('slug')->unique();
            $table->string('dominio_personalizado')->nullable();
            $table->string('logo')->nullable();
            $table->string('capa')->nullable();
            $table->json('cores')->nullable(); // Ex: {"primaria": "#0ea5e9", "secundaria": "#38b2ac"}
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
        });

        Schema::create('site_servicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->string('titulo');
            $table->text('descricao');
            $table->decimal('preco', 10, 2)->nullable();
            $table->string('imagem')->nullable();
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('empresa_site')->onDelete('cascade');
        });

        Schema::create('site_depoimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->string('nome');
            $table->string('foto')->nullable();
            $table->tinyInteger('nota')->default(5);
            $table->text('comentario');
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('empresa_site')->onDelete('cascade');
        });

        Schema::create('site_contatos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->string('tipo');  // Ex: whatsapp, email, telefone
            $table->string('valor'); // Ex: número ou endereço de e-mail
            $table->timestamps();

            $table->foreign('site_id')->references('id')->on('empresa_site')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_contatos');
        Schema::dropIfExists('site_depoimentos');
        Schema::dropIfExists('site_servicos');
        Schema::dropIfExists('empresa_site');
    }
};
