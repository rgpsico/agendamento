<?php

namespace App\Services;

use App\Models\Receita;
use Illuminate\Support\Facades\DB;

class FinanceiroReceitaService
{
    /**
     * Lançar uma nova receita automática
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
     * Lançar receita manual
     */
    public function lancarReceitaManual(array $dados): Receita
    {
        return $this->lancarReceita($dados);
    }

    /**
     * Listar todas as receitas
     */
    public function listarReceitas()
    {
        return Receita::with(['categoria', 'usuario', 'empresa'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    /**
     * Buscar receita pelo ID
     */
    public function buscarReceita(int $id): Receita
    {
        return Receita::with(['categoria', 'usuario', 'empresa'])->findOrFail($id);
    }

    /**
     * Atualizar receita
     */
    public function atualizarReceita(int $id, array $dados): bool
    {
        $receita = Receita::findOrFail($id);
        return $receita->update($dados);
    }

    /**
     * Excluir receita
     */
    public function excluirReceita(int $id): bool
    {
        $receita = Receita::findOrFail($id);
        return $receita->delete();
    }
}
