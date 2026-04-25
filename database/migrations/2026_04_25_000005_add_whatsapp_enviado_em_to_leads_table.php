<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (! Schema::hasColumn('leads', 'whatsapp_enviado_em')) {
                $table->timestamp('whatsapp_enviado_em')->nullable()->after('email_enviado_em');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'whatsapp_enviado_em')) {
                $table->dropColumn('whatsapp_enviado_em');
            }
        });
    }
};
