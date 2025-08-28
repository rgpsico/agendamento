<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localizacao extends Model
{
    use HasFactory;

    protected $table = 'localizacoes';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nome',
        'tipo',       // pais, estado, cidade, zona, bairro
        'parent_id',  // referência para hierarquia
    ];

    // Relacionamento pai
    public function parent()
    {
        return $this->belongsTo(Localizacao::class, 'parent_id');
    }

    // Relacionamento filhos
    public function children()
    {
        return $this->hasMany(Localizacao::class, 'parent_id');
    }

    // Relacionamento com empresas
    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_localizacao', 'localizacao_id', 'empresa_id');
    }
}
