<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('widget_sites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('nome');                          // Ex: "Site Surf Club"
            $table->string('token', 48)->unique();           // token do snippet de rastreamento
            $table->string('dominio')->nullable();           // Ex: surfclub.com.br (informativo)
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('widget_sites');
    }
};
