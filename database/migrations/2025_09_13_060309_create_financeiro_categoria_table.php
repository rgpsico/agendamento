<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financeiro_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->enum('tipo', ['despesa', 'receita']); // indica se a categoria é de despesa ou receita
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financeiro_categorias');
    }
};
