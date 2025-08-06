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
        Schema::create('site_visualizacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_site_id')->constrained('empresa_site')->onDelete('cascade');
            $table->ipAddress('ip')->nullable(); // IP do visitante
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visualizacaos');
    }
};
