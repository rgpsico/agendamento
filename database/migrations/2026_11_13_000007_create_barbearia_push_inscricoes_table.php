<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Celulares do barbeiro inscritos para receber aviso de cliente novo na fila (Web Push). */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('barbearia_push_inscricoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->index();
            $table->text('endpoint');
            $table->char('endpoint_hash', 64)->unique(); // o mesmo aparelho nunca entra duas vezes
            $table->string('chave_p256dh');
            $table->string('chave_auth');
            $table->string('aparelho', 190)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barbearia_push_inscricoes');
    }
};
