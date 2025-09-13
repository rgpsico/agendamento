<?php

namespace App\Services;

use App\Models\Receitas;
use Illuminate\Support\Facades\DB;

class FinanceiroReceitaService
{
    /**
     * Lançar uma nova receita
     */
    public function lancarReceita(array $dados): Receitas
    {
        return DB::transaction(function () use ($dados) {
            return Receitas::create([
                'descricao' => $dados['descricao'] ?? 'Receita automática',
                'valor'     => $dados['valor'],
                'data'      => $dados['data'] ?? now(),
                'categoria_id' => $dados['categoria_id'] ?? null,
                'user_id'   => $dados['user_id'] ?? auth()->id(),
                'status'    => $dados['status'] ?? 'confirmado',
            ]);
        });
    }
}
