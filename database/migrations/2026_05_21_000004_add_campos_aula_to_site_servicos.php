<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_servicos', function (Blueprint $table) {
            $table->string('nivel', 60)->nullable()->after('titulo');        // "Iniciantes", "Privativa", "Kids · 6-12 anos"
            $table->string('duracao', 60)->nullable()->after('preco');       // "2h", "1h30", "8 sessões"
            $table->string('capacidade', 80)->nullable()->after('duracao');  // "até 4 alunos", "1 aluno"
            $table->string('info_extra', 100)->nullable()->after('capacidade'); // "🎥 vídeo análise", "📈 plano personalizado"
            $table->boolean('destaque')->default(false)->after('info_extra'); // card destacado (featured)
        });
    }

    public function down(): void
    {
        Schema::table('site_servicos', function (Blueprint $table) {
            $table->dropColumn(['nivel', 'duracao', 'capacidade', 'info_extra', 'destaque']);
        });
    }
};
