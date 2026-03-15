<?php 

// database/migrations/xxxx_xx_xx_create_receitas_recorrentes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('receitas_recorrentes', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->enum('frequencia', ['diario', 'semanal', 'mensal', 'anual']); // tipo da recorrência
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresa')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('receitas_recorrentes');
    }
};
