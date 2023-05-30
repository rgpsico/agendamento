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
        Schema::create('aulas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('professor_id');
            $table->unsignedBigInteger('dia_id');
            $table->timestamp('data_hora');
            $table->string('local');
            $table->integer('capacidade');
            $table->timestamps();


            $table->foreign('dia_id')->references('id')->on('dias_da_semana');
            $table->foreign('professor_id')->references('id')->on('professores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
