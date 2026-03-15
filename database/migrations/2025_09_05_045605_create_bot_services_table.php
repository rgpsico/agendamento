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
        Schema::create('bot_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bot_id');
            $table->string('nome_servico');
            $table->string('professor')->nullable();
            $table->string('horario')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->timestamps();

            // Relacionamento
            $table->foreign('bot_id')->references('id')->on('bots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_services');
    }
};
