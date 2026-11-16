<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Números de WhatsApp (senders do Twilio) ligados a uma empresa e a um bot.
     * A mensagem que chega no número vira conversa da empresa e a IA do bot responde.
     */
    public function up(): void
    {
        Schema::create('whatsapp_canais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresa')->cascadeOnDelete();
            $table->foreignId('bot_id')->nullable()->constrained('bots')->nullOnDelete();
            $table->string('provedor', 20)->default('twilio');
            $table->string('numero', 20)->unique(); // E.164, ex.: +5521999999999
            $table->boolean('ativo')->default(true);
            $table->boolean('ia_ativa')->default(true);
            $table->timestamps();
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->string('canal', 20)->nullable()->after('telefone'); // null/site = chat do site; whatsapp
            $table->foreignId('whatsapp_canal_id')->nullable()->after('canal')->constrained('whatsapp_canais')->nullOnDelete();
            $table->index(['empresa_id', 'canal', 'telefone']);
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['whatsapp_canal_id']);
            $table->dropIndex(['empresa_id', 'canal', 'telefone']);
            $table->dropColumn(['canal', 'whatsapp_canal_id']);
        });

        Schema::dropIfExists('whatsapp_canais');
    }
};
