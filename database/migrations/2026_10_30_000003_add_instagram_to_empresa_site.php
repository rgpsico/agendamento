<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->string('instagram', 120)->nullable()->after('galeria_url');
        });
    }

    public function down(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->dropColumn('instagram');
        });
    }
};
