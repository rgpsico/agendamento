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

    // protected $casts = [
    //     'cores' => 'array',
    //     'sobre_itens' => 'array',
    //     'ativo' => 'boolean',
    // ];

    // // ✅ ADICIONAR: Método para customizar Route Model Binding
    // public function resolveRouteBinding($value, $field = null)
    // {
    //     // Tentar encontrar o registro
    //     $result = $this->where($field ?? $this->getRouteKeyName(), $value)->first();
        
    //     // Se não encontrar, retornar null ao invés de dar erro 404
    //     return $result;
    // }

    // // Relacionamentos
    // public function empresa()
    // {
    //     return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    // }

    // public function servicos()
    // {
    //     return $this->hasMany(SiteServico::class, 'site_id');
    // }

    // public function depoimentos()
    // {
    //     return $this->hasMany(SiteDepoimento::class, 'site_id');
    // }

    // public function contatos()
    // {
    //     return $this->hasMany(SiteContato::class, 'site_id');
    // }
}