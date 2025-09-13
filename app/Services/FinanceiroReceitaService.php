<?php

namespace App\Services;

use App\Models\Receita;
use Illuminate\Support\Facades\DB;

class FinanceiroReceitaService
{
    /**
     * Lançar uma nova receita
     */
    public function lancarReceita(array $dados): Receita
    {
        return DB::transaction(function () use ($dados) {
            return Receita::create([
                'descricao'     => $dados['descricao'] ?? 'Receita automática',
                'valor'         => $dados['valor'],
                'data'          => $dados['data'] ?? now(),
                'categoria_id'  => $dados['categoria_id'] ?? null,
                'usuario_id'    => $dados['usuario_id'],
                'status'        => $dados['status'] ?? 'confirmado',
                'empresa_id'    => $dados['empresa_id']
            ]);
        });
    }

    /**
     * Listar receitas
     */
    public function listarReceitas()
    {
        return Receita::with(['categoria', 'usuario', 'empresa'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }
}
