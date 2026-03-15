<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loc_empresa_bairro', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('bairro_id');

            $table->foreign('empresa_id')
                  ->references('id')
                  ->on('empresa') // tabela de empresas
                  ->onDelete('cascade');

            $table->foreign('bairro_id')
                  ->references('id')
                  ->on('loc_bairros') // tabela de bairros
                  ->onDelete('cascade');

            $table->primary(['empresa_id', 'bairro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loc_empresa_bairro');
    }
};
