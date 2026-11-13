<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Barbeiro que sai da equipe fica inativo (o histórico de atendimentos continua).
        Schema::table('professores', function (Blueprint $table) {
            $table->boolean('ativo')->default(true);
        });

        // QR de check-in da parede: só quem escaneia o QR da barbearia entra na fila (a menos que o dono desligue).
        Schema::table('empresa', function (Blueprint $table) {
            $table->string('checkin_token', 40)->nullable()->unique();
            $table->boolean('checkin_exige_qr')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropUnique(['checkin_token']);
            $table->dropColumn(['checkin_token', 'checkin_exige_qr']);
        });
        Schema::table('professores', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
};
