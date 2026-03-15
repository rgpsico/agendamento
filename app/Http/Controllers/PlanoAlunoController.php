<?php

namespace App\Http\Controllers;

use App\Models\Alunos;
use App\Models\Pagamento;
use App\Models\PlanoAluno;
use Illuminate\Http\Request;

class PlanoAlunoController extends Controller
{
    // Listar todos os planos
    public function index()
    {
        $planos = PlanoAluno::all();
        return response()->json($planos);
    }

    // Criar novo plano
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'required|numeric',
            'duracao_dias' => 'required|integer',
        ]);

        $plano = PlanoAluno::create($validated);

        // Redireciona para a listagem de planos com mensagem de sucesso
        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano criado com sucesso!');
    }


    // Mostrar um plano específico
    public function show($id)
    {
        $plano = PlanoAluno::findOrFail($id);
        return response()->json($plano);
    }

    // Atualizar um plano
    public function update(Request $request, $id)
    {
        $plano = PlanoAluno::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
            'valor' => 'sometimes|required|numeric',
            'duracao_dias' => 'sometimes|required|integer',
        ]);

        $plano->update($validated);

        // Redireciona para a listagem de planos com mensagem de sucesso
        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano atualizado com sucesso!');
    }


    // Deletar um plano
    public function destroy($id)
    {
        $plano = PlanoAluno::findOrFail($id);
        $plano->delete();

        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano Excluido com sucesso!');
    }


    // Tela de listagem
    public function indexView()
    {
        $planos = PlanoAluno::all();
        return view('admin.aluno.planos.index', compact('planos'));
    }

    // Tela de criação
    public function create()
    {
        return view('admin.aluno.planos.create');
    }

    // Tela de edição
    public function edit(PlanoAluno $plano)
    {
        return view('admin.aluno.planos.edit', compact('plano'));
    }

    // Funções store, update e destroy continuam iguais

    public function vincular(Request $request)
    {
        $busca = $request->query('busca');

        $alunosQuery = Alunos::with('usuario')
            ->whereHas('usuario', function ($query) {
                $query->whereRaw('LOWER(tipo_usuario) = ?', ['aluno']);
            });

        if ($busca) {
            $alunosQuery->whereHas('usuario', function ($query) use ($busca) {
                $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('email', 'like', "%{$busca}%")
                    ->orWhere('telefone', 'like', "%{$busca}%");
            });
        }

        $alunos = $alunosQuery
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $alunoSelecionado = null;
        $historicoPlanos = collect();
        $pagamentos = collect();

        if ($request->filled('aluno_id')) {
            $alunoSelecionado = Alunos::with([
                'usuario',
                'planos' => function ($query) {
                    $query->orderByDesc('aluno_planos.created_at');
                },
            ])->find($request->query('aluno_id'));

            if ($alunoSelecionado) {
                $historicoPlanos = $alunoSelecionado->planos;
                $pagamentos = Pagamento::where('aluno_id', $alunoSelecionado->id)
                    ->latest()
                    ->get();
            } else {
                return redirect()
                    ->route('alunos.planos.vincular', array_filter(['busca' => $busca]))
                    ->with('error', 'Aluno não encontrado.');
            }
        }

        $planos = PlanoAluno::orderBy('nome')->get();

        return view('admin.aluno.planos.vincular', [
            'planos' => $planos,
            'alunos' => $alunos,
            'alunoSelecionado' => $alunoSelecionado,
            'historicoPlanos' => $historicoPlanos,
            'pagamentos' => $pagamentos,
            'busca' => $busca,
        ]);
    }

    public function vincularStore(Request $request)
    {
        $validated = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'plano_id' => 'required|exists:planos_alunos,id',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'status' => 'required|in:ativo,inativo,cancelado',
            'valor_pago' => 'nullable|numeric',
            'forma_pagamento' => 'nullable|string|max:255',
        ]);

        $aluno = Alunos::findOrFail($validated['aluno_id']);

        $aluno->planos()->syncWithoutDetaching([
            $validated['plano_id'] => [
                'data_inicio' => $validated['data_inicio'] ?? null,
                'data_fim' => $validated['data_fim'] ?? null,
                'status' => $validated['status'],
                'valor_pago' => $validated['valor_pago'] ?? null,
                'forma_pagamento' => $validated['forma_pagamento'] ?? null,
            ],
        ]);

        return redirect()
            ->route('alunos.planos.vincular', [
                'aluno_id' => $aluno->id,
            ])
            ->with('success', 'Plano vinculado ao aluno com sucesso!');
    }

}

