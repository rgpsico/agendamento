<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campanhas', function (Blueprint $table) {
            $table->uuid('public_token')->nullable()->unique()->after('id');
            $table->string('formulario_titulo')->nullable()->after('ativo');
            $table->text('formulario_descricao')->nullable()->after('formulario_titulo');
            $table->string('formulario_botao')->default('Quero agendar uma aula')->after('formulario_descricao');
            $table->boolean('formulario_ativo')->default(true)->after('formulario_botao');
        });

        DB::table('campanhas')
            ->whereNull('public_token')
            ->orderBy('id')
            ->get()
            ->each(function ($campanha) {
                DB::table('campanhas')
                    ->where('id', $campanha->id)
                    ->update(['public_token' => (string) Str::uuid()]);
            });
    }

    public function down(): void
    {
        Schema::table('campanhas', function (Blueprint $table) {
            $table->dropUnique(['public_token']);
            $table->dropColumn([
                'public_token',
                'formulario_titulo',
                'formulario_descricao',
                'formulario_botao',
                'formulario_ativo',
            ]);
        });
    }
};
