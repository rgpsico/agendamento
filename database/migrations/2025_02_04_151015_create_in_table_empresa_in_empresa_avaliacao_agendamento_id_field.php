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
        Schema::table('empresa_avaliacao', function (Blueprint $table) {
            $table->foreignId('agendamento_id')->nullable()->constrained('agendamentos')->onDelete('cascade')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_avaliacao', function (Blueprint $table) {
            $table->dropColumn('agendamento_id');
        });
    }
};
