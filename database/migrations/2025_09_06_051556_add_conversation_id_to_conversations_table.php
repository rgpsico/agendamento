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
        Schema::table('messages', function (Blueprint $table) {
            // adiciona a coluna se não existir
            if (!Schema::hasColumn('messages', 'conversation_id')) {
                $table->unsignedBigInteger('conversation_id')->after('id')->nullable();

                // cria a foreign key
                $table->foreign('conversation_id')
                    ->references('id')
                    ->on('conversations')
                    ->onDelete('cascade'); // se a conversa for deletada, apaga as mensagens também
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropColumn('conversation_id');
        });
    }
};
