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
        Schema::create('site_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('empresa_site')->onDelete('cascade');
            $table->string('chave'); // ex: exibir_sobre
            $table->json('valor')->nullable(); // true, false ou array
            $table->timestamps();

            $table->unique(['site_id', 'chave']); // garante que cada chave seja única por site
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_configuracoes');
    }
};
