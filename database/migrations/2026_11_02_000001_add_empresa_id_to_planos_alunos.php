<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cada plano de alunos passa a pertencer a uma empresa. Antes a tabela era compartilhada: qualquer
 * empresa via e alterava os planos de todas. A coluna e nullable (nao apaga nem exige nada nos dados
 * existentes); o codigo sempre grava a empresa ao criar um plano.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planos_alunos', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->index('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresa')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('planos_alunos', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropIndex(['empresa_id']);
            $table->dropColumn('empresa_id');
        });
    }
};
