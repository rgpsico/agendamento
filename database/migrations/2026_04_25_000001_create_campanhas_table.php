<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campanhas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('empresa')->cascadeOnDelete();
            $table->string('nome');
            $table->string('canal');
            $table->date('inicio')->nullable();
            $table->date('fim')->nullable();
            $table->decimal('custo', 10, 2)->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'ativo']);
            $table->index(['tenant_id', 'canal']);
        });

        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'campanha_id')) {
                $table->foreign('campanha_id')->references('id')->on('campanhas')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'campanha_id')) {
                $table->dropForeign(['campanha_id']);
            }
        });

        Schema::dropIfExists('campanhas');
    }
};
