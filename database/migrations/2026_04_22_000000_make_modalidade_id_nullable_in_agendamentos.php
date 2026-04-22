<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropForeign(['modalidade_id']);
            $table->unsignedBigInteger('modalidade_id')->nullable()->change();
            $table->foreign('modalidade_id')->references('id')->on('modalidade')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropForeign(['modalidade_id']);
            $table->unsignedBigInteger('modalidade_id')->nullable(false)->change();
            $table->foreign('modalidade_id')->references('id')->on('modalidade');
        });
    }
};
