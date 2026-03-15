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
        Schema::create('empresa_planos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('empresa_id')
                ->constrained('empresa')
                ->onDelete('cascade');

            $table->foreignId('plano_id')
                ->constrained('planos')
                ->onDelete('cascade');

            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->enum('status', ['ativo', 'pendente', 'cancelado'])->default('pendente');
            $table->decimal('valor', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_planos');
    }
};
