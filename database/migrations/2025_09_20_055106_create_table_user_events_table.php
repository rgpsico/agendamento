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
        Schema::create('user_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('event_type')->index();
            $table->json('payload')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('source')->nullable();
            $table->timestamps(); // created_at used
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_events');
    }
};
