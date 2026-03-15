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
        Schema::create('receitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pagamento_id')->nullable()->constrained('pagamentos')->onDelete('cascade');
            $table->foreignId('categoria_id')->nullable()->constrained('financeiro_categorias')->onDelete('set null');
            $table->string('descricao')->nullable();
            $table->decimal('valor', 10, 2);
            $table->enum('status', ['PENDENTE', 'RECEBIDA', 'CANCELADA'])->default('PENDENTE');
            $table->timestamp('data_recebimento')->nullable();
            $table->foreignId('empresa_id')->constrained('empresa')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receitas');
    }
};
