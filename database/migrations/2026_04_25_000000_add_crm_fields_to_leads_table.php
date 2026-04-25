<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (! Schema::hasColumn('leads', 'tenant_id')) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained('empresa')->nullOnDelete();
            }

            if (! Schema::hasColumn('leads', 'pipeline_status')) {
                $table->string('pipeline_status')->default('novo_lead')->after('status');
            }

            if (! Schema::hasColumn('leads', 'valor_estimado')) {
                $table->decimal('valor_estimado', 10, 2)->nullable()->after('interesse');
            }

            if (! Schema::hasColumn('leads', 'campanha_id')) {
                $table->foreignId('campanha_id')->nullable()->after('responsavel_id');
            }
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->index(['tenant_id', 'pipeline_status'], 'leads_tenant_pipeline_status_idx');
            $table->index(['tenant_id', 'origem'], 'leads_tenant_origem_idx');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_tenant_pipeline_status_idx');
            $table->dropIndex('leads_tenant_origem_idx');

            if (Schema::hasColumn('leads', 'campanha_id')) {
                $table->dropColumn('campanha_id');
            }

            if (Schema::hasColumn('leads', 'valor_estimado')) {
                $table->dropColumn('valor_estimado');
            }

            if (Schema::hasColumn('leads', 'pipeline_status')) {
                $table->dropColumn('pipeline_status');
            }

            if (Schema::hasColumn('leads', 'tenant_id')) {
                $table->dropConstrainedForeignId('tenant_id');
            }
        });
    }
};
