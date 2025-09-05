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
    Schema::create('bot_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained()->onDelete('cascade');
            $table->text('mensagem_usuario')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->text('resposta_bot')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('tokens_usados')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_logs');
    }
};
