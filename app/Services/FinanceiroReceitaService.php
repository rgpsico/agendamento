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
            $campos = [
                'pagamento_id'     => $dados['pagamento_id'] ?? null,
                'descricao'        => $dados['descricao'] ?? 'Receita automática',
                'valor'            => $dados['valor'],
                'data_recebimento' => array_key_exists('data_recebimento', $dados) ? $dados['data_recebimento'] : ($dados['data'] ?? now()),
                'data_vencimento'  => $dados['data_vencimento'] ?? null,
                'metodo_pagamento' => $dados['metodo_pagamento'] ?? null,
                'categoria_id'     => $dados['categoria_id'] ?? $this->categoriaDoPagamento($dados['pagamento_id'] ?? null),
                'usuario_id'       => $dados['usuario_id'] ?? null,
                'status'           => $dados['status'] ?? 'confirmado',
                'empresa_id'       => $dados['empresa_id'] ?? null,
            ];

            return Receita::create($campos);
        });
    }

    /**
     * Categoria do serviço agendado. Cada serviço tem uma categoria de receita (criada junto com ele),
     * então o faturamento já sai separado por serviço (ex.: Pilates x Fisioterapia).
     */
    private function categoriaDoPagamento(?int $pagamentoId): ?int
    {
        if (! $pagamentoId) {
            return null;
        }

        $pagamento = \App\Models\Pagamento::with('agendamento.servico')->find($pagamentoId);

        return $pagamento?->agendamento?->servico?->categoria_id;
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
        return Receita::with([
            'categoria',                    // Adicione isso se não estiver carregando
            'usuario',                      // Adicione isso para $receita->usuario
            'empresa',
            'pagamento.agendamento.modalidade', // Já ok para serviço
            'pagamento.aluno.usuario'       // Já ok para aluno/usuario
        ])->orderBy('created_at', 'desc')
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
