<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('token')->unique()->nullable()->after('id');
            $table->timestamp('interessado_em')->nullable()->after('email_enviado_em');
            $table->string('whatsapp_confirmado')->nullable()->after('interessado_em');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['token', 'interessado_em', 'whatsapp_confirmado']);
        });
    }
};
