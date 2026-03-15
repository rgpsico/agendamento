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
        Schema::create('loc_bairros', function (Blueprint $table) {
            $table->id();
             $table->foreignId('cidade_id')->constrained('loc_cidades')->cascadeOnDelete();
            $table->foreignId('zona_id')->constrained('loc_zonas')->cascadeOnDelete();
            $table->string('nome'); // ex: Copacabana, Ipanema
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loc_bairros');
    }
};
