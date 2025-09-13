<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Pagamento;
use App\Services\FinanceiroReceitaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceitaController extends Controller
{
    protected $receitaService;

    public function __construct(FinanceiroReceitaService $receitaService)
    {
        $this->receitaService = $receitaService;
    }

    /**
     * Listar todas as receitas.
     */
    public function index()
    {
        $receitas = $this->receitaService->listarReceitas();
        return view('admin.financeiro.receitas.index', compact('receitas'));
    }

    /**
     * Formulário de lançamento manual de receita.
     */
    public function create()
    {
        $professor = Auth::user()->professor;

        if (!$professor) {
            auth()->logout();
            return redirect()->back()->with('error', 'Professor não encontrado.');
        }

        $alunos = $professor->alunos()->with('usuario')->get();

        $agendamentos = Agendamento::with(['aluno.usuario', 'modalidade'])
            ->whereIn('aluno_id', $alunos->pluck('id'))
            ->get();

        return view('admin.financeiro.receitas.create', compact('alunos', 'agendamentos'));
    }

    /**
     * Salvar nova receita manual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'agendamento_id' => 'required|exists:agendamentos,id',
            'aluno_id' => 'required|exists:alunos,id',
            'valor' => 'required|numeric|min:0',
            'status' => 'required|in:PENDING,RECEIVED',
            'metodo_pagamento' => 'required|string',
        ]);

        $this->receitaService->lancarReceitaManual($request->all());

        return redirect()->route('financeiro.receitas.index')
            ->with('success', 'Receita lançada com sucesso!');
    }

    /**
     * Editar receita.
     */
    public function edit($id)
    {
        $receita = $this->receitaService->buscarReceita($id);
        $agendamentos = Agendamento::with('aluno')->get();

        return view('admin.financeiro.receitas.edit', compact('receita', 'agendamentos'));
    }

    /**
     * Atualizar receita.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
            'status' => 'required|in:PENDING,RECEIVED',
            'metodo_pagamento' => 'required|string',
        ]);

        $this->receitaService->atualizarReceita($id, $request->only(['valor', 'status', 'metodo_pagamento', 'data_vencimento']));

        return redirect()->route('financeiro.receitas.index')
            ->with('success', 'Receita atualizada com sucesso!');
    }

    /**
     * Excluir receita.
     */
    public function destroy($id)
    {
        $this->receitaService->excluirReceita($id);

        return redirect()->route('financeiro.receitas.index')
            ->with('success', 'Receita excluída com sucesso!');
    }
}
