<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsaasWebhookLogsTable extends Migration
{
    public function up()
    {
        Schema::create('asaas_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event')->nullable(); // Tipo de evento (ex.: PAYMENT_RECEIVED)
            $table->json('payload')->nullable(); // Payload completo do webhook
            $table->string('status'); // Status do processamento (ex.: success, invalid_token, invalid_payload)
            $table->text('message')->nullable(); // Mensagem adicional (ex.: erro ou informação)
            $table->string('payment_id')->nullable(); // ID do pagamento, se aplicável
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asaas_webhook_logs');
    }
}