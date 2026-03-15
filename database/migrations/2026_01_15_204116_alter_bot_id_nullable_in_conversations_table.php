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
            $table->dropForeign(['bot_id']);
            $table->foreignId('bot_id')->nullable()->change();
            $table->foreign('bot_id')->references('id')->on('bots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['bot_id']);
            $table->foreignId('bot_id')->nullable(false)->change();
            $table->foreign('bot_id')->references('id')->on('bots')->onDelete('cascade');   
        });
    }
};
