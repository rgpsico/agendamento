<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('widget_sites', function (Blueprint $table) {
            $table->string('whatsapp_selector', 255)->nullable()->after('dominio');
            // Ex: #ht-ctc-chat  ou  .ht-ctc-chat  ou  .ctc-analytics
        });
    }

    public function down(): void
    {
        Schema::table('widget_sites', function (Blueprint $table) {
            $table->dropColumn('whatsapp_selector');
        });
    }
};
