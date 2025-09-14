<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\FinanceiroCategoria;
use App\Models\Pagamento;
use App\Models\Receita;
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
    $request = request(); 
    $query = Receita::with(['usuario', 'categoria', 'pagamento.agendamento.modalidade']);

    if ($request->aluno) {
        $query->whereHas('usuario', function ($q) use ($request) {
            $q->where('nome', 'like', '%' . $request->aluno . '%');
        });
    }

    if ($request->descricao) {
        $query->where('descricao', 'like', '%' . $request->descricao . '%');
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->metodo_pagamento) {
        $query->whereHas('pagamento', function ($q) use ($request) {
            $q->where('metodo_pagamento', $request->metodo_pagamento);
        });
    }

    // 👇 clone da query para calcular o total filtrado
    $totalReceitas = (clone $query)->sum('valor');

    $receitas = $query->orderBy('created_at', 'desc')
                      ->paginate(15)
                      ->withQueryString();

    return view('admin.financeiro.receitas.index', compact('receitas', 'totalReceitas'));
}




    /**
     * Formulário de lançamento manual de receita.
     */
    public function create()
    {
        $professor = Auth::user()->professor;
        $categorias = FinanceiroCategoria::where('tipo', 'receita')->get();
        if (!$professor) {
            auth()->logout();
            return redirect()->back()->with('error', 'Professor não encontrado.');
        }

        $alunos = $professor->alunos()->with('usuario')->get();

        $agendamentos = Agendamento::with(['aluno.usuario', 'modalidade'])
            ->whereIn('aluno_id', $alunos->pluck('id'))
            ->get();

        return view('admin.financeiro.receitas.create', compact('alunos', 'agendamentos','categorias'));
    }

    /**
     * Salvar nova receita manual.
     */
    public function store(Request $request)
{
    $request->validate([
        'aluno_id'         => 'required|exists:alunos,id',
     
        'valor'            => 'required|numeric|min:0',
        'metodo_pagamento' => 'required|string',
        'status'           => 'required|in:PENDENTE,RECEBIDA',
        'data_vencimento'  => 'nullable|date',
        'empresa_id'       => 'required|exists:empresa,id',
    ]);

    $dadosReceita = [
        'descricao'        => 'Pagamento da aula',
        'valor'            => $request->valor,
        'data'             => now(), // data de lançamento
        'data_vencimento'  => $request->data_vencimento, // vencimento
        'categoria_id'     => null, // ajustar se tiver categorias
        'usuario_id'       => auth()->id(),
        'status'           => $request->status,
        'empresa_id'       => $request->empresa_id,     
        'aluno_id'         => $request->aluno_id,
        'metodo_pagamento' => $request->metodo_pagamento,
        'pagamento_id'     => null, 
    ];

    $this->receitaService->lancarReceitaManual($dadosReceita);

    return redirect()->route('financeiro.receitas.index')
        ->with('success', 'Receita lançada com sucesso!');
}



    /**
     * Editar receita.
     */
    public function edit($id)
    {
           $categorias = FinanceiroCategoria::where('tipo', 'receita')->get();
        $receita = $this->receitaService->buscarReceita($id);
        $agendamentos = Agendamento::with('aluno')->get();

        return view('admin.financeiro.receitas.edit', compact('receita', 'agendamentos','categorias'));
    }

    /**
     * Atualizar receita.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'descricao'        => 'nullable|string|max:255',
            'valor'            => 'required|numeric|min:0',
            'status'           => 'required|in:PENDENTE,RECEBIDA',
            'metodo_pagamento' => 'required|string',
            'data_vencimento'  => 'nullable|date',
            'empresa_id'       => 'required|exists:empresa,id',
            'categoria_id'     => 'nullable|exists:financeiro_categorias,id'
        ]);

        $this->receitaService->atualizarReceita(
            $id,
            $request->only([
                'descricao',
                'valor',
                'status',
                'metodo_pagamento',
                'data_vencimento',
                'categoria_id',
                'empresa_id'
            ])
        );

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
