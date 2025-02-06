<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoGeral extends Model
{
    use HasFactory;

    protected $table = 'configuracoes_geral';

    use HasFactory;


    protected $fillable = [
        'agendamento_tipo',
        'whatsapp_numero',
        'login_image',
        'register_image',
        'home_mode',
        'carousel_images',
        'sistema_tipo',
    ];

    protected $casts = [
        'carousel_images' => 'array',
    ];
}
