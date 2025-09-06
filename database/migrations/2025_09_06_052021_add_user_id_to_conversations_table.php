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
    Schema::table('conversations', function (Blueprint $table) {
        if (!Schema::hasColumn('conversations', 'user_id')) {
            $table->unsignedBigInteger('user_id')->nullable()->after('bot_id');
            $table->foreign('user_id')->references('id')->on('usuarios')->onDelete('cascade');
        }
    });
}

public function down(): void
{
    Schema::table('conversations', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}

};
