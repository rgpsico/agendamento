<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('gympass_checkins', function (Blueprint $table) {
            $table->id();
            $table->string('unique_token');
            $table->string('event_type');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number');
            $table->decimal('lat', 10, 8);
            $table->decimal('lon', 11, 8);
            $table->integer('gym_id');
            $table->string('gym_title');
            $table->integer('product_id');
            $table->string('product_description');
            $table->timestamp('timestamp');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gympass_checkins');
    }
};
