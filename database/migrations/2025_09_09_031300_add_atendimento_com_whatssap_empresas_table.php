<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
               $table->boolean('atendimento_com_whatsapp')->default(false)->after('atendimento_com_ia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
                $table->boolean('atendimento_com_whatsapp')->default(false)->after('atendimento_com_ia');
        });
    }
};
