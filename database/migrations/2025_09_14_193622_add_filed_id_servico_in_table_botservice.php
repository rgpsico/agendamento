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
      Schema::table('bot_services', function (Blueprint $table) {
    $table->unsignedBigInteger('servico_id')->nullable()->after('bot_id');
    $table->foreign('servico_id')->references('id')->on('servicos')->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_services', function (Blueprint $table) {
            $table->dropColumn('data_vencimento');
        });
    }
};
