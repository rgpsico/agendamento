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
        Schema::create('modalidade', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->default('Surf');
            $table->timestamps();
        });

        DB::table('modalidade')->insert([
            ['id' => 1, 'nome' => 'Surf'],
            ['id' => 2, 'nome' => 'BodyBoard'],
            ['id' => 3, 'nome' => 'Passeios'],
            ['id' => 4, 'nome' => 'Corrida'],
            ['id' => 5, 'nome' => 'Futevôlei'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalidade');
    }
};
