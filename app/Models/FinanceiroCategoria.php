<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceiroCategoria extends Model
{
    use HasFactory;

    protected $table = 'financeiro_categorias';

    protected $fillable = [
        'nome',
        'descricao',
        'tipo', // opcional: 'despesa' ou 'receita' se você quiser diferenciar
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
