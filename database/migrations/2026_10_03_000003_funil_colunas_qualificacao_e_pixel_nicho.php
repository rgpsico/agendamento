<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lead_qualificacoes', function (Blueprint $table) {
            // origem da campanha (UTMs do anúncio)
            $table->string('utm_source', 100)->nullable()->after('user_agent');
            $table->string('utm_medium', 100)->nullable()->after('utm_source');
            $table->string('utm_campaign', 150)->nullable()->after('utm_medium');
            $table->string('host', 150)->nullable()->after('utm_campaign');

            // teste grátis e compra
            $table->unsignedBigInteger('usuario_id')->nullable()->after('lead_id');
            $table->unsignedBigInteger('empresa_id')->nullable()->after('usuario_id');
            $table->string('negocio_nome')->nullable()->after('sobre_negocio');
            $table->timestamp('trial_inicio')->nullable();
            $table->timestamp('trial_fim')->nullable();
            $table->string('asaas_subscription_id', 60)->nullable();
            $table->string('asaas_customer_id', 60)->nullable();
            $table->timestamp('cliente_desde')->nullable();

            $table->index('utm_campaign');
            $table->index('usuario_id');
        });

        Schema::table('nicho_configuracoes', function (Blueprint $table) {
            $table->string('pixel_id', 30)->nullable()->after('cor_secundaria');
        });
    }

    public function down(): void
    {
        Schema::table('lead_qualificacoes', function (Blueprint $table) {
            $table->dropIndex(['utm_campaign']);
            $table->dropIndex(['usuario_id']);
            $table->dropColumn(['utm_source', 'utm_medium', 'utm_campaign', 'host', 'usuario_id', 'empresa_id', 'negocio_nome',
                'trial_inicio', 'trial_fim', 'asaas_subscription_id', 'asaas_customer_id', 'cliente_desde']);
        });
        Schema::table('nicho_configuracoes', function (Blueprint $table) {
            $table->dropColumn('pixel_id');
        });
    }
};
