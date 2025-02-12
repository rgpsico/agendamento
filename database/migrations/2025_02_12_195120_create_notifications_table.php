<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // ID da notificação
            $table->foreignId('user_id')->nullable()->constrained('usuarios')->onDelete('cascade'); // Notificação para clientes/professores/alunos
            $table->foreignId('empresa_id')->nullable()->constrained('empresa')->onDelete('cascade'); // Notificação para empresas
            $table->string('title'); // Título da notificação
            $table->text('message'); // Mensagem da notificação
            $table->enum('status', ['unread', 'read'])->default('unread'); // Status da notificação
            $table->enum('type', ['info', 'warning', 'success', 'error'])->default('info'); // Tipo da notificação
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
