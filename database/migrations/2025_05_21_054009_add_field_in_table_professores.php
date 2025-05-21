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
        Schema::table('professores', function (Blueprint $table) {
            $table->string('asaas_customer_id')->nullable();;
            $table->string('asaas_wallet_id')->nullable()->after('asaas_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_professores', function (Blueprint $table) {
            //
        });
    }
};
