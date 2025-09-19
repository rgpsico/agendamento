<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bot_services', function (Blueprint $table) {
            $table->dropColumn(['nome_servico', 'professor', 'horario', 'valor']);
        });
    }

    public function down(): void
    {
        Schema::table('bot_services', function (Blueprint $table) {
            $table->string('nome_servico')->nullable();
            $table->string('professor')->nullable();
            $table->string('horario')->nullable();
            $table->decimal('valor', 8, 2)->nullable();
        });
    }
};
