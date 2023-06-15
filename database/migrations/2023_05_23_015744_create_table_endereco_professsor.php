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
        Schema::create('professor_endereco', function (Blueprint $table) {
            $table->unsignedBigInteger('professor_id');
            $table->string('endereco')->default(null);
            $table->string('cidade')->default(null);
            $table->string('estado')->default(null);
            $table->string('cep')->default(null);
            $table->string('uf')->default(null);
            $table->string('pais')->default(null);
            $table->foreign('professor_id')->references('id')->on('professores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas_endereco');
    }
};
