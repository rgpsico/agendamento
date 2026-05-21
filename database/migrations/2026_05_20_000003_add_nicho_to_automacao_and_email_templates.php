<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sequências: allow tenant_id null (super admin) + nicho scope
        Schema::table('automacao_sequencias', function (Blueprint $table) {
            $table->string('nicho', 50)->nullable()->after('tenant_id')->index();
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
        });

        // Templates: allow tenant_id null (super admin) + nicho scope
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('nicho', 50)->nullable()->after('tenant_id')->index();
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
        });

        // Envios: allow tenant_id null
        Schema::table('automacao_envios', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('automacao_sequencias', function (Blueprint $table) {
            $table->dropColumn('nicho');
        });
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('nicho');
        });
    }
};
