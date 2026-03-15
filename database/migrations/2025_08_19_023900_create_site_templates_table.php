<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_templates', function (Blueprint $table) {
            $table->id();
            $table->string('titulo'); // Ex: "Padrão", "Minimalista"
            $table->string('slug')->unique(); // Ex: "default", "minimal"
            $table->text('descricao')->nullable(); // Descrição opcional
            $table->string('preview_image')->nullable(); // Caminho da imagem de preview
            $table->string('path_view')->nullable(); // Ex: "templates.default"
            $table->timestamps();
        });

        Schema::table('empresa_site', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->nullable()->after('empresa_id');

            $table->foreign('template_id')
                ->references('id')
                ->on('site_templates')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresa_site', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn('template_id');
        });

        Schema::dropIfExists('site_templates');
    }
};
