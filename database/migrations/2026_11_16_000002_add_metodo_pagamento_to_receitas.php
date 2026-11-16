<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** A forma de pagamento (Pix, dinheiro, cartão...) de uma receita lançada à mão era descartada ao salvar. */
    public function up(): void
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->string('metodo_pagamento', 30)->nullable()->after('valor');
        });
    }

    public function down(): void
    {
        Schema::table('receitas', function (Blueprint $table) {
            $table->dropColumn('metodo_pagamento');
        });
    }
};
