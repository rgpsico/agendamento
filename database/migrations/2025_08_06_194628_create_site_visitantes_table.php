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
        Schema::create('site_visitantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_site_id')->constrained('empresa_site')->onDelete('cascade');
            $table->string('session_id'); // hash único para identificar visitante
            $table->ipAddress('ip')->nullable();
            $table->timestamps();

            $table->unique(['empresa_site_id', 'session_id']); // garante 1 visitante por sessão por site
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visitantes');
    }
};
