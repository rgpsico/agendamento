<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaSite extends Model
{
    use HasFactory;

    protected $table = 'empresa_site';

    protected $fillable = [
        'empresa_id',
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
    ];

    protected $casts = [
        'cores' => 'array',
        'sobre_itens' => 'array',
        'ativo' => 'boolean',
    ];


    // Relacionamento com a empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relacionamento com os serviços do site
    public function servicos()
    {
        return $this->hasMany(SiteServico::class, 'site_id');
    }

    // Relacionamento com os depoimentos
    public function depoimentos()
    {
        return $this->hasMany(SiteDepoimento::class, 'site_id');
    }

    // Relacionamento com os contatos
    public function contatos()
    {
        return $this->hasMany(SiteContato::class, 'site_id');
    }
}
