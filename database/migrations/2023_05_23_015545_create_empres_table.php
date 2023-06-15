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
        Schema::create('empresa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('avatar')->default('avatardefault.png');
            $table->string('nome');
            $table->string('descricao')->default(null);
            $table->string('telefone');
            $table->string('cnpj')->default(null);
            $table->foreign('user_id')->references('id')->on('usuarios');
            $table->timestamps();
        });

        Schema::create('empresa_endereco', function (Blueprint $table) {
            $table->uuid('empresa_id');
            $table->string('endereco')->default(null);
            $table->string('cidade')->default(null);
            $table->string('estado')->default(null);
            $table->string('cep')->default(null);
            $table->string('uf')->default(null);
            $table->string('pais')->default(null);
            $table->foreign('empresa_id')->references('id')->on('empresa'); // Alterado 'id' para 'uuid'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
