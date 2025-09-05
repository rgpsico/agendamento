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
        Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->string('from');    // ex: whatsapp:+5511999999999
        $table->string('to');      // seu número
        $table->enum('role', ['user','assistant','system']);
        $table->text('body');
        $table->string('twilio_sid')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
