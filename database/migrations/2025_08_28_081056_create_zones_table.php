<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loc_zonas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cidade_id')
                  ->constrained('loc_cidades') // referência correta
                  ->cascadeOnDelete();
            $table->string('nome'); // ex: Zona Sul, Zona Norte
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loc_zonas');
    }
};
