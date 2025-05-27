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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agendamento_id');
            $table->unsignedBigInteger('aluno_id');
            $table->string('asaas_payment_id')->unique()->collation('utf8mb4_bin')->nullable(); // Nullable para pagamento na hora
            $table->string('status'); // Ex.: PENDING, RECEIVED, CONFIRMED, PRESENCIAL
            $table->decimal('valor', 10, 2); // Valor do pagamento
            $table->string('metodo_pagamento'); // Ex.: PRESENCIAL, CREDIT_CARD, PIX, BOLETO
            $table->dateTime('data_vencimento')->nullable(); // Para boletos
            $table->string('url_boleto')->nullable(); // URL do boleto, se aplicável
            $table->string('qr_code_pix')->nullable(); // QR code do Pix, se aplicável
            $table->text('resposta_api')->nullable(); // JSON bruto da resposta da API
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('agendamento_id')->references('id')->on('agendamentos')->onDelete('cascade');
            $table->foreign('aluno_id')->references('id')->on('alunos')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagamentos');
    }
};
