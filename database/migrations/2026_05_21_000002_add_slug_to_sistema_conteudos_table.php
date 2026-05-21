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
        Schema::table('sistema_conteudos', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('titulo');
            $table->boolean('publico')->default(true)->after('status'); // se aparece no blog público
            $table->string('autor', 120)->nullable()->after('publico');
            $table->string('meta_descricao', 300)->nullable()->after('autor');
        });

        // Gerar slug para registros existentes
        DB::table('sistema_conteudos')->get()->each(function ($row) {
            $base = Str::slug($row->titulo);
            $slug = $base;
            $i    = 1;
            while (DB::table('sistema_conteudos')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('sistema_conteudos')->where('id', $row->id)->update(['slug' => $slug]);
        });

        // Tornar slug not nullable após preencher
        Schema::table('sistema_conteudos', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sistema_conteudos', function (Blueprint $table) {
            $table->dropColumn(['slug', 'publico', 'autor', 'meta_descricao']);
        });
    }
};
