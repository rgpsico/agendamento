<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaSite extends Model
{
    use HasFactory;

    protected $table = 'empresa_site'; // Confirmar se o nome está correto

    protected $fillable = [
        'empresa_id',
        'template_id', // ✅ novo campo
        'titulo',
        'slug',
        'dominio_personalizado',
        'logo',
        'capa',
        'cores',
        'descricao',
        'ativo',
        'sobre_titulo',
        'sobre_descricao',
        'sobre_imagem',
        'sobre_itens',
        'whatsapp',
        'vhost_criado',
        'atendimento_com_ia',
        'atendimento_com_whatsapp'
    ];

    protected $casts = [
        'cores' => 'array',
        'sobre_itens' => 'array',
        'ativo' => 'boolean',
    ];


    public function trackingCodes()
    {
        return $this->hasMany(TrackingCode::class, 'site_id');
    }

    public function configuracao()
    {
        return $this->hasOne(\App\Models\SiteConfiguracao::class, 'site_id');
    }


    // Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(SiteTemplate::class, 'template_id');
    }

    public function servicos()
    {
        return $this->hasMany(Servicos::class, 'empresa_id', 'empresa_id');
    }

    public function siteServicos()
    {
        return $this->hasMany(SiteServico::class, 'site_id', 'id');
    }

    public function depoimentos()
    {
        return $this->hasMany(SiteDepoimento::class, 'site_id');
    }

    public function contatos()
    {
        return $this->hasMany(SiteContato::class, 'site_id');
    }

    public function endereco()
    {
        return $this->belongsTo(EmpresaEndereco::class, 'empresa_id', 'empresa_id');
    }

    public function visualizacoes()
    {
        return $this->hasMany(SiteVisualizacao::class, 'empresa_site_id');
    }

    public function cliquesWhatsapp()
    {
        return $this->hasMany(SiteCliqueWhatsapp::class, 'empresa_site_id');
    }

    public function visitantes()
    {
        return $this->hasMany(SiteVisitante::class, 'empresa_site_id');
    }
}
