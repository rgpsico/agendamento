<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroCategoria extends Model
{
    use HasFactory, \App\Models\Concerns\DaEmpresaLogada;

    /** Categorias sem empresa (empresa_id nulo) são padrões visíveis para todas. */
    protected static bool $empresaOpcional = true;

    protected $table = 'financeiro_categorias';

    protected $fillable = [
        'nome',
        'descricao',
        'tipo', // opcional: 'despesa' ou 'receita' se você quiser diferenciar
        'empresa_id',
    ];

    // Relacionamentos
    public function despesasRecorrentes()
    {
        return $this->hasMany(DespesaRecorrente::class, 'categoria_id');
    }

    public function receitasRecorrentes()
    {
        return $this->hasMany(ReceitaRecorrente::class, 'categoria_id');
    }
}
