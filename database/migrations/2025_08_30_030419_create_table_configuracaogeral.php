<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('configuracoesgeral', function (Blueprint $table) {
            $table->id();
            $table->string('nome_sistema')->nullable();
            $table->string('logo_header')->nullable();
            $table->string('logo_footer')->nullable();
            $table->text('modal_boas_vindas')->nullable();
            $table->string('login_image')->nullable();
            $table->string('register_image')->nullable();
            $table->string('home_mode')->default('slider');
            $table->json('slider_images')->nullable();
            $table->string('home_image')->nullable();
            $table->string('home_title')->nullable();
            $table->string('sistema_tipo')->default('passeio');
            $table->string('instagram')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('email')->nullable();
            $table->text('politica_privacidade')->nullable();
            $table->text('termos_condicoes')->nullable();
            $table->string('agendamento_tipo')->nullable();
            $table->string('whatsapp_numero')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('configuracoesgeral');
    }
};