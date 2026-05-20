<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modalidade', function (Blueprint $table) {
            $table->string('nicho', 50)->nullable()->after('nome')->index();
        });

        // Modalidades existentes pertencem ao pilates (nicho original)
        DB::table('modalidade')->whereNull('nicho')->update(['nicho' => 'pilates']);
    }

    public function down(): void
    {
        Schema::table('modalidade', function (Blueprint $table) {
            $table->dropColumn('nicho');
        });
    }
};
