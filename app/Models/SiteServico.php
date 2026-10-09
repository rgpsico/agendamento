<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteServico extends Model
{
    protected $fillable = [
        'titulo', 'descricao', 'imagem', 'site_id', 'tipo', 'preco',
        'nivel', 'duracao', 'capacidade', 'info_extra', 'destaque',
        'traducoes', 'imagem_credito', 'imagem_credito_url',
    ];

    protected $casts = [
        'features'  => 'array',
        'destaque'  => 'boolean',
        'traducoes' => 'array',
    ];

    public function fotos()
    {
        return $this->hasMany(SiteServicoFoto::class, 'site_servico_id')->orderBy('ordem')->orderBy('id');
    }

    public function site()
    {
        return $this->belongsTo(EmpresaSite::class, 'site_id');
    }
}
