<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planos_alunos', function (Blueprint $table) {
            $table->string('tipo', 20)->default('livre')->after('nome');        // 'semanal' | 'livre'
            $table->json('dias_semana')->nullable()->after('duracao_dias');      // ["seg","qua","sex"]
            $table->string('horario', 10)->nullable()->after('dias_semana');    // "07:00"
            $table->tinyInteger('aulas_semana')->nullable()->after('horario');  // 2, 3...
            $table->string('periodicidade', 20)->nullable()->after('aulas_semana'); // mensal, avulso...

            // duracao_dias pode ser nulo agora (planos sem prazo definido)
            $table->integer('duracao_dias')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('planos_alunos', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'dias_semana', 'horario', 'aulas_semana', 'periodicidade']);
        });
    }
};
