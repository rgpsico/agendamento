<?php

// 1. Migration para tabela de configurações de pagamento
// database/migrations/xxxx_create_payment_configurations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentConfigurationsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->boolean('pix_enabled')->default(true);
            $table->boolean('cartao_enabled')->default(true);
            $table->boolean('presencial_enabled')->default(true);
            $table->json('pix_config')->nullable(); // Para configurações específicas do PIX
            $table->json('cartao_config')->nullable(); // Para configurações específicas do cartão
            $table->json('presencial_config')->nullable(); // Para configurações específicas do presencial
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresa');
            $table->unique('empresa_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_configurations');
    }
}
