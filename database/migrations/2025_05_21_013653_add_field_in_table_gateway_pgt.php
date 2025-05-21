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
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->string('mode')->nullable(); // sandbox ou production
            $table->json('methods')->nullable(); // Métodos de pagamento
            $table->string('split_account')->nullable(); // Conta do dono do SaaS
            $table->string('tariff_type')->nullable(); // fixed ou percentage
            $table->decimal('tariff_value', 8, 2)->nullable(); // Valor da tarifa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->dropColumn(['mode', 'methods', 'split_account', 'tariff_type', 'tariff_value']);
        });
    }
};
