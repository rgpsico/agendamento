<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id'); // 🔹 Relacionado ao site
            $table->string('name', 100); // Nome amigável: "Google Analytics", "Facebook Pixel"
            $table->string('provider', 50); // google, facebook, tiktok, etc
            $table->string('code', 255); // Ex: G-4ZMP2C63TR
            $table->enum('type', ['analytics','ads','pixel','other'])->default('other');
            $table->text('script')->nullable(); // Script completo (se necessário)
            $table->boolean('status')->default(true); // Ativo ou não
            $table->timestamps();

            $table->foreign('site_id')
                  ->references('id')
                  ->on('empresa_site') // 🔹 FK correta
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_codes');
    }
};
