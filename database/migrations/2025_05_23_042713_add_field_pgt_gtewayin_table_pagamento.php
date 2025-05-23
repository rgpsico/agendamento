<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->unsignedBigInteger('pagamento_gateway_id')->nullable()->after('aluno_id');
            $table->foreign('pagamento_gateway_id')->references('id')->on('payment_gateways')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropForeign(['pagamento_gateway_id']);
            $table->dropColumn('pagamento_gateway_id');
        });
    }
};
