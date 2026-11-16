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

        // Filtros
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
            $query->where(function ($q) use ($request) {
                // forma de pagamento da própria receita (manual) ou do pagamento do agendamento
                $q->where('metodo_pagamento', $request->metodo_pagamento)->orWhere(fn ($qq) => $qq->whereNull('metodo_pagamento')
                    ->whereHas('pagamento', fn ($p) => $p->where('metodo_pagamento', $request->metodo_pagamento)));
            });
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Novo: filtro por período
        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_vencimento', [$request->data_inicio, $request->data_fim]);
        }

        // Clone da query para total filtrado
        $totalReceitas = (clone $query)->sum('valor');

        // Paginação
        $receitas = $query->orderBy('created_at', 'desc')
                        ->paginate(15)
                        ->withQueryString();

        $categorias = FinanceiroCategoria::where('tipo', 'receita')->orderBy('nome')->get();

        return view('admin.financeiro.receitas.index', compact('receitas', 'totalReceitas', 'categorias'));
    }





    /**
     * Formulário de lançamento manual de receita.
     */
    public function create()
    {
        $categorias = FinanceiroCategoria::where('tipo', 'receita')->orderBy('nome')->get();

        return view('admin.financeiro.receitas.create', [
            'alunos'       => $this->alunosDaEmpresa(),
            'agendamentos' => collect(),
            'categorias'   => $categorias,
        ]);
    }

    /** Alunos/pacientes de todos os profissionais da(s) empresa(s) do usuário logado (dono ou profissional). */
    private function alunosDaEmpresa()
    {
        $empresas = \App\Support\EmpresasDoUsuario::ids(Auth::user());

        return \App\Models\Professor::whereIn('empresa_id', $empresas ?: [0])
            ->with('alunos.usuario')
            ->get()
            ->pluck('alunos')
            ->flatten()
            ->unique('id')
            ->sortBy(fn ($aluno) => mb_strtolower($aluno->usuario->nome ?? ''))
            ->values();
    }

    /**
     * Salvar nova receita manual.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao'        => 'nullable|string|max:255',
            'aluno_id'         => 'nullable|exists:alunos,id',
            'categoria_id'     => 'nullable|exists:financeiro_categorias,id',
            'valor'            => 'required|numeric|min:0',
            'metodo_pagamento' => 'required|in:' . implode(',', array_keys(Receita::METODOS)),
            'status'           => 'required|in:PENDENTE,RECEBIDA',
            'data_recebimento' => 'nullable|date',
            'data_vencimento'  => 'nullable|date',
        ]);

        // Aluno e categoria só da própria empresa (a categoria já é filtrada pelo model).
        $aluno = $request->filled('aluno_id')
            ? $this->alunosDaEmpresa()->firstWhere('id', (int) $request->aluno_id)
            : null;
        abort_if($request->filled('aluno_id') && ! $aluno, 422, 'Aluno não encontrado nesta empresa.');

        $categoriaId = $request->filled('categoria_id')
            ? FinanceiroCategoria::whereKey($request->categoria_id)->value('id')
            : null;

        $recebida = $request->status === 'RECEBIDA';

        $this->receitaService->lancarReceitaManual([
            'descricao'        => $request->descricao ?: ($aluno ? 'Pagamento de ' . ($aluno->usuario->nome ?? 'aluno') : 'Receita avulsa'),
            'valor'            => $request->valor,
            'data_recebimento' => $recebida ? ($request->data_recebimento ?: now()) : null,
            'data_vencimento'  => $request->data_vencimento,
            'metodo_pagamento' => $request->metodo_pagamento,
            'categoria_id'     => $categoriaId,
            // usuario_id = de quem é a receita (o aluno); sem aluno, quem lançou
            'usuario_id'       => $aluno->usuario_id ?? auth()->id(),
            'status'           => $request->status,
            'pagamento_id'     => null,
            // empresa_id: preenchido pelo model com a empresa do usuário logado
        ]);

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
        $agendamentos = collect(); // a tela de edição não usa; antes carregava os agendamentos de todas as empresas

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
            'metodo_pagamento' => 'required|in:' . implode(',', array_keys(Receita::METODOS)),
            'data_vencimento'  => 'nullable|date',
            'categoria_id'     => 'nullable|exists:financeiro_categorias,id'
        ]);

        $receita = $this->receitaService->buscarReceita($id); // 404 se for de outra empresa
        $dados = $request->only(['descricao', 'valor', 'status', 'metodo_pagamento', 'data_vencimento', 'categoria_id']);

        // Só categorias da própria empresa (o model filtra); id de outra empresa vira "sem categoria".
        $dados['categoria_id'] = $request->filled('categoria_id')
            ? FinanceiroCategoria::whereKey($request->categoria_id)->value('id')
            : null;

        // Marcou como recebida agora: registra a data do recebimento.
        if ($request->status === 'RECEBIDA' && ! $receita->data_recebimento) {
            $dados['data_recebimento'] = now();
        }

        $this->receitaService->atualizarReceita($id, $dados);

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
