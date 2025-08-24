<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->nullable()->after('id');

            $table->foreign('template_id')
                  ->references('id')
                  ->on('templates')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
         Schema::table('empresa_site', function (Blueprint $table) {
            if (Schema::hasColumn('empresa_site', 'template_id')) {
                $table->dropForeign(['template_id']);
                $table->dropColumn('template_id');
            }
        });
    }
};
