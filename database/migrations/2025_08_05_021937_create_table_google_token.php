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
        Schema::create('google_ads_tokens', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('empresa_id'); // FK para a tabela empresas/clientes
            $table->string('google_account_id')->nullable(); // ID da conta Google Ads do cliente (opcional)

            $table->text('access_token');
            $table->text('refresh_token');

            $table->timestamp('access_token_expires_at')->nullable();

            $table->timestamps();

            // FK e índices
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->index('empresa_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('google_ads_tokens');
    }
};
