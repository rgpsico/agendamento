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
            $table->foreignId('bot_id')->nullable()->constrained('bots')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->dropColumn('bot_id'); // remove o campo ao reverter
        });
    }
};
