<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receitas_recorrentes', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->after('descricao'); // adiciona coluna
            $table->foreign('categoria_id')->references('id')->on('financeiro_categorias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('receitas_recorrentes', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
    }
};
