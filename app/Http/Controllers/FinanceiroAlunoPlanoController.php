<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceiroAlunoPlanoController extends Controller
{
    /**
     * Exibe a listagem de vínculos de alunos com planos e seus pagamentos.
     */
    public function index()
    {
        $pageTitle = 'Financeiro - Vínculos de Planos';

        $vinculos = collect([
            [
                'id' => '#2024-001',
                'aluno' => 'Ana Souza',
                'plano' => 'Plano Premium',
                'valor' => 249.90,
                'metodo' => 'Cartão de Crédito',
                'status' => 'Pago',
                'data_pagamento' => '05/05/2024',
                'proxima_fatura' => '05/06/2024',
            ],
            [
                'id' => '#2024-002',
                'aluno' => 'Bruno Lima',
                'plano' => 'Plano Mensal',
                'valor' => 149.90,
                'metodo' => 'PIX',
                'status' => 'Pendente',
                'data_pagamento' => '—',
                'proxima_fatura' => '12/05/2024',
            ],
            [
                'id' => '#2024-003',
                'aluno' => 'Carla Mendes',
                'plano' => 'Plano Semestral',
                'valor' => 799.90,
                'metodo' => 'Boleto',
                'status' => 'Aguardando Confirmação',
                'data_pagamento' => '28/04/2024',
                'proxima_fatura' => '28/10/2024',
            ],
        ]);

        return view('admin.financeiro.vinculos.index', compact('pageTitle', 'vinculos'));
    }

    /**
     * Exibe o formulário de criação de um novo vínculo.
     */
    public function create()
    {
        $pageTitle = 'Registrar Pagamento e Vincular Plano';

        $alunos = [
            ['id' => 1, 'nome' => 'Ana Souza', 'documento' => '123.456.789-00'],
            ['id' => 2, 'nome' => 'Bruno Lima', 'documento' => '987.654.321-00'],
            ['id' => 3, 'nome' => 'Carla Mendes', 'documento' => '321.654.987-00'],
        ];

        $planos = [
            ['id' => 1, 'nome' => 'Plano Premium', 'duracao' => '12 meses', 'valor' => 249.90],
            ['id' => 2, 'nome' => 'Plano Mensal', 'duracao' => 'Mensal', 'valor' => 149.90],
            ['id' => 3, 'nome' => 'Plano Semestral', 'duracao' => '6 meses', 'valor' => 799.90],
        ];

        $metodosPagamento = ['Cartão de Crédito', 'PIX', 'Boleto', 'Dinheiro'];

        return view('admin.financeiro.vinculos.create', compact('pageTitle', 'alunos', 'planos', 'metodosPagamento'));
    }
}
