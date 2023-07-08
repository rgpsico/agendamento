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
        Schema::create('disponibilidade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_professor');
            $table->unsignedBigInteger('id_dia');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();

            $table->foreign('id_professor')->references('id')->on('professores')->onDelete('cascade');
            $table->foreign('id_dia')->references('id')->on('dias_da_semana')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilidade');
    }
};
