<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Capa da barbearia (foto larga que aparece na lista e no topo da página da barbearia). */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->string('capa')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropColumn('capa');
        });
    }
};
