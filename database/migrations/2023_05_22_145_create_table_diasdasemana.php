<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dias_da_semana', function (Blueprint $table) {
            $table->id();
            $table->integer('dia');
            $table->string('nome_dia');
        });

        DB::table('dias_da_semana')->insert([
            ['id' => 1, 'dia' => 1, 'nome_dia' => 'Segunda-feira'],
            ['id' => 2, 'dia' => 2, 'nome_dia' => 'Terça-feira'],
            ['id' => 3, 'dia' => 3, 'nome_dia' => 'Quarta-feira'],
            ['id' => 4, 'dia' => 4, 'nome_dia' => 'Quinta-feira'],
            ['id' => 5, 'dia' => 5, 'nome_dia' => 'Sexta-feira'],
            ['id' => 6, 'dia' => 6, 'nome_dia' => 'Sábado'],
            ['id' => 7, 'dia' => 7, 'nome_dia' => 'Domingo'],
        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dias_da_semana');
    }
};
