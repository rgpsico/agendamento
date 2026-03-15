<?php 


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loc_empresa_localizacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresa')->cascadeOnDelete();
            $table->foreignId('localizacao_id')->constrained('loc_bairros')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loc_empresa_localizacao');
    }
};
