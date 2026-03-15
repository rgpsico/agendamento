<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
      Schema::create('conversations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('bot_id')->constrained()->onDelete('cascade'); 
        $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null'); // alterado para usuarios
        $table->text('mensagem'); // mensagem enviada ou recebida
        $table->enum('tipo', ['user', 'bot'])->default('user'); // quem enviou
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
