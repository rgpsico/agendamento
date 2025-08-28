<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loc_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pais_id')
                  ->constrained('loc_paises') // tabela de países com prefixo
                  ->cascadeOnDelete();
            $table->string('nome');
            $table->string('codigo', 5)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loc_estados');
    }
};
