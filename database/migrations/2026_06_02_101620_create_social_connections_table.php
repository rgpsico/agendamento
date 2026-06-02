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
        Schema::create('social_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->unique();
            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');

            // Facebook Page
            $table->string('facebook_page_id')->nullable();
            $table->string('facebook_page_name')->nullable();
            $table->text('facebook_page_token')->nullable();   // Page Access Token (long-lived)

            // Instagram Business Account
            $table->string('instagram_account_id')->nullable();
            $table->string('instagram_username')->nullable();

            // User token (para renovação futura)
            $table->text('user_access_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_connections');
    }
};
