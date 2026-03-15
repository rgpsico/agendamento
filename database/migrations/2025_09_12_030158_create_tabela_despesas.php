<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->string('categoria')->nullable();
            $table->enum('status', ['PENDING', 'PAID'])->default('PENDING');
            $table->date('data_vencimento')->nullable();
           $table->foreignId('empresa_id')
            ->constrained('empresa') // <-- aqui informa o nome correto
            ->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('despesas');
    }
};
