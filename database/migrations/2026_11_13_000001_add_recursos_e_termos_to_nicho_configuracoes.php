<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nicho_configuracoes', function (Blueprint $table) {
            // Liga/desliga funções por nicho: {"fila_checkin": true}. Nulo = nada extra (nichos antigos não mudam).
            $table->json('recursos')->nullable()->after('pixel_id');
            // Palavras do nicho: {"profissional": "Barbeiro", "cliente": "Cliente"}. Nulo = palavras de sempre.
            $table->json('termos')->nullable()->after('recursos');
        });
    }

    public function down(): void
    {
        Schema::table('nicho_configuracoes', function (Blueprint $table) {
            $table->dropColumn(['recursos', 'termos']);
        });
    }
};
