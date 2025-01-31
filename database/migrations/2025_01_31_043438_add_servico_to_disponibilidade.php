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
        Schema::table('disponibilidade', function (Blueprint $table) {
            $table->unsignedBigInteger('id_servico')->after('id_professor');
            $table->foreign('id_servico')->references('id')->on('servicos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disponibilidade', function (Blueprint $table) {
            $table->dropColumn('id_servico');
        });
    }
};
