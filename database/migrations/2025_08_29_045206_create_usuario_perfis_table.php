<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuario_perfis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->foreignId('perfil_id')->constrained('perfis')->cascadeOnDelete();
            $table->json('meta')->nullable(); // campos extras dinâmicos, ex: empresa_id
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_perfis');
    }
};
