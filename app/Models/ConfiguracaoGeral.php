<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoGeral extends Model
{
    use HasFactory;

    protected $table = 'configuracoesgeral';

    protected $fillable = [
    'nome_sistema',
    'logo_header',
    'logo_footer',
    'modal_boas_vindas',
    'login_image',
    'register_image',
    'home_mode',
    'slider_images',
    'home_image',
    'home_title',
    'sistema_tipo',
    'instagram',
    'whatsapp',
    'tiktok',
    'email',
    'politica_privacidade',
    'termos_condicoes',
    'agendamento_tipo',
    'whatsapp_numero',
];


    protected $casts = [
        'slider_images' => 'array',
    ];
}