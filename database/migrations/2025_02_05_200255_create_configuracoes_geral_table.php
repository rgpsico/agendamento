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
        Schema::create('configuracoes_geral', function (Blueprint $table) {
            $table->id();
            $table->string('agendamento_tipo')->default('horarios');
            $table->string('whatsapp_numero')->nullable();
            $table->string('login_image')->nullable();
            $table->string('register_image')->nullable();
            $table->string('home_mode')->default('carousel');
            $table->json('carousel_images')->nullable();
            $table->string('sistema_tipo')->default('passeio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes_geral');
    }
};
