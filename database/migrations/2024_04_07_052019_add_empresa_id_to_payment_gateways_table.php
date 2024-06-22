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
        // Schema::table('payment_gateways', function (Blueprint $table) {
        //     $table->unsignedBigInteger('empresa_id')->after('id'); // Adiciona após a coluna 'id'
        //     $table->foreign('empresa_id')->references('id')->on('empresas'); // Define a chave estrangeira
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']); // Remove a chave estrangeira
            $table->dropColumn('empresa_id'); // Remove a coluna
        });
    }
};
