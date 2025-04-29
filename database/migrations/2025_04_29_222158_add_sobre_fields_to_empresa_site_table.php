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
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->text('sobre_titulo')->nullable();
            $table->text('sobre_descricao')->nullable();
            $table->string('sobre_imagem')->nullable();
            $table->json('sobre_itens')->nullable(); // Ex: [{ "icone": "fa-medal", "titulo": "...", "descricao": "..." }]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            //
        });
    }
};
