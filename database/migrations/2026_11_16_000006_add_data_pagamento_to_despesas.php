<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Despesa só tinha vencimento; agora guarda quando foi paga (usada nos relatórios). */
    public function up(): void
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->date('data_pagamento')->nullable()->after('data_vencimento');
        });

        // Já pagas: sem a data real, usa o vencimento (ou a data do lançamento).
        DB::table('despesas')->whereIn('status', ['PAID', 'PAGA', 'PAGO'])->whereNull('data_pagamento')
            ->update(['data_pagamento' => DB::raw('COALESCE(data_vencimento, DATE(created_at))')]);
    }

    public function down(): void
    {
        Schema::table('despesas', function (Blueprint $table) {
            $table->dropColumn('data_pagamento');
        });
    }
};
