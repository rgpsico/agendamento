<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('despesas', function (Blueprint $table) {
            // Remove coluna antiga
            $table->dropColumn('categoria');

            // Adiciona coluna categoria_id
            $table->foreignId('categoria_id')
                  ->after('valor')
                  ->constrained('despesas_categorias')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('despesas', function (Blueprint $table) {
            // Remove categoria_id
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');

            // Adiciona coluna categoria antiga de volta
            $table->string('categoria')->after('valor');
        });
    }
};
