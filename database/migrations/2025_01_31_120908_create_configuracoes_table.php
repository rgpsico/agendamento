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
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('chave');
            $table->text('valor');
            $table->enum('tipo', ['string', 'integer', 'boolean', 'json'])->default('string');
            $table->timestamps();

            // Se a tabela 'empresas' existir, adiciona a chave estrangeira
            if (Schema::hasTable('empresas')) {
                $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            }

            $table->unique(['empresa_id', 'chave']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes');
    }
};
