<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('configuracoesgeral', function (Blueprint $table) {
            $table->string('favicon')->nullable()->after('logo_footer');
            $table->text('footer_descricao')->nullable()->after('favicon');
            $table->string('footer_endereco')->nullable()->after('footer_descricao');
        });
    }

    public function down()
    {
        Schema::table('configuracoesgeral', function (Blueprint $table) {
            $table->dropColumn(['favicon', 'footer_descricao', 'footer_endereco']);
        });
    }
};
