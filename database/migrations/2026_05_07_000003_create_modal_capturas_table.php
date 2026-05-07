<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modal_capturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('nome');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('botao_texto')->default('Quero participar!');
            $table->string('cor_primaria', 7)->default('#2a5298');
            $table->boolean('campo_nome')->default(true);
            $table->boolean('campo_email')->default(true);
            $table->boolean('campo_telefone')->default(true);
            $table->string('mensagem_sucesso')->default('Recebemos seus dados! Em breve entraremos em contato.');
            $table->string('token', 64)->unique();
            $table->unsignedBigInteger('campanha_id')->nullable();
            $table->string('origem_lead')->default('site');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modal_capturas');
    }
};
