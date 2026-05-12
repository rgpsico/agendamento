<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('widget_eventos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->string('session_id', 48);               // ID de sessão (sessionStorage)
            $table->string('tipo', 30);                     // visita|whatsapp|tempo|bot_open|modal_view|modal_convert
            $table->string('pagina', 500)->nullable();       // URL da página
            $table->string('referrer', 500)->nullable();     // de onde veio
            $table->string('dispositivo', 10)->default('desktop'); // desktop|mobile|tablet
            $table->unsignedSmallInteger('duracao')->nullable();   // segundos na página (evento tempo)
            $table->string('meta', 255)->nullable();         // dados extras (ex: número do WA clicado)
            $table->timestamps();

            $table->index(['site_id', 'tipo']);
            $table->index(['site_id', 'created_at']);
            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widget_eventos');
    }
};
