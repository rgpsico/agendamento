<?php

namespace App\Http\Controllers;

use App\Models\Alunos;
use App\Models\Pagamento;
use App\Models\PlanoAluno;
use Illuminate\Http\Request;

class PlanoAlunoController extends Controller
{
    public function indexView()
    {
        $planos = PlanoAluno::orderBy('nome')->get();
        return view('admin.aluno.planos.index', compact('planos'));
    }

    public function create()
    {
        return view('admin.aluno.planos.create');
    }

    public function edit(PlanoAluno $plano)
    {
        return view('admin.aluno.planos.edit', compact('plano'));
    }

    public function store(Request $request)
    {
        $tipo = $request->input('tipo', 'livre');

        if ($tipo === 'semanal') {
            $validated = $request->validate([
                'nome'          => 'required|string|max:255',
                'valor'         => 'required|numeric|min:0',
                'dias_semana'   => 'required|array|min:1',
                'dias_semana.*' => 'in:seg,ter,qua,qui,sex,sab,dom',
                'horario'       => 'nullable|string|max:10',
                'duracao_meses' => 'nullable|integer|min:1',
                'aulas_semana'  => 'nullable|integer|min:1',
                'descricao'     => 'nullable|string',
            ]);

            PlanoAluno::create([
                'nome'          => $validated['nome'],
                'valor'         => $validated['valor'],
                'dias_semana'   => json_encode($validated['dias_semana']),
                'horario'       => $validated['horario'] ?? null,
                'duracao_dias'  => isset($validated['duracao_meses']) ? $validated['duracao_meses'] * 30 : null,
                'aulas_semana'  => $validated['aulas_semana'] ?? count($validated['dias_semana']),
                'descricao'     => $validated['descricao'] ?? null,
                'periodicidade' => 'mensal',
                'tipo'          => 'semanal',
            ]);

        } else {
            $validated = $request->validate([
                'nome'          => 'required|string|max:255',
                'valor'         => 'required|numeric|min:0',
                'descricao'     => 'nullable|string',
                'duracao_dias'  => 'nullable|integer|min:1',
                'periodicidade' => 'nullable|string|max:20',
            ]);

            PlanoAluno::create([
                'nome'          => $validated['nome'],
                'valor'         => $validated['valor'],
                'descricao'     => $validated['descricao'] ?? null,
                'duracao_dias'  => $validated['duracao_dias'] ?? null,
                'periodicidade' => $validated['periodicidade'] ?? 'avulso',
                'tipo'          => 'livre',
            ]);
        }

        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano criado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $plano = PlanoAluno::findOrFail($id);
        $tipo  = $request->input('tipo', $plano->tipo ?? 'livre');

        if ($tipo === 'semanal') {
            $validated = $request->validate([
                'nome'          => 'required|string|max:255',
                'valor'         => 'required|numeric|min:0',
                'dias_semana'   => 'required|array|min:1',
                'dias_semana.*' => 'in:seg,ter,qua,qui,sex,sab,dom',
                'horario'       => 'nullable|string|max:10',
                'duracao_meses' => 'nullable|integer|min:1',
                'aulas_semana'  => 'nullable|integer|min:1',
                'descricao'     => 'nullable|string',
            ]);

            $plano->update([
                'nome'         => $validated['nome'],
                'valor'        => $validated['valor'],
                'dias_semana'  => json_encode($validated['dias_semana']),
                'horario'      => $validated['horario'] ?? null,
                'duracao_dias' => isset($validated['duracao_meses']) ? $validated['duracao_meses'] * 30 : null,
                'aulas_semana' => $validated['aulas_semana'] ?? count($validated['dias_semana']),
                'descricao'    => $validated['descricao'] ?? null,
                'tipo'         => 'semanal',
            ]);

        } else {
            $validated = $request->validate([
                'nome'          => 'required|string|max:255',
                'valor'         => 'required|numeric|min:0',
                'descricao'     => 'nullable|string',
                'duracao_dias'  => 'nullable|integer|min:1',
                'periodicidade' => 'nullable|string|max:20',
            ]);

            $plano->update([
                'nome'          => $validated['nome'],
                'valor'         => $validated['valor'],
                'descricao'     => $validated['descricao'] ?? null,
                'duracao_dias'  => $validated['duracao_dias'] ?? null,
                'periodicidade' => $validated['periodicidade'] ?? 'avulso',
                'dias_semana'   => null,
                'horario'       => null,
                'tipo'          => 'livre',
            ]);
        }

        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano atualizado com sucesso!');
    }

    public function destroy($id)
    {
        PlanoAluno::findOrFail($id)->delete();
        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano removido com sucesso!');
    }

    /* ── API endpoints (mantidos para compatibilidade) ─── */

    public function index()
    {
        return response()->json(PlanoAluno::orderBy('nome')->get());
    }

    public function show($id)
    {
        return response()->json(PlanoAluno::findOrFail($id));
    }

    /* ── Vincular aluno a plano ─────────────────────────── */

    public function vincular(Request $request)
    {
        $busca = $request->query('busca');

        $alunosQuery = Alunos::with('usuario')
            ->whereHas('usuario', fn($q) => $q->whereRaw('LOWER(tipo_usuario) = ?', ['aluno']));

        if ($busca) {
            $alunosQuery->whereHas('usuario', fn($q) =>
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('email', 'like', "%{$busca}%")
                  ->orWhere('telefone', 'like', "%{$busca}%")
            );
        }

        $alunos           = $alunosQuery->orderByDesc('id')->paginate(10)->withQueryString();
        $alunoSelecionado = null;
        $historicoPlanos  = collect();
        $pagamentos       = collect();

        if ($request->filled('aluno_id')) {
            $alunoSelecionado = Alunos::with([
                'usuario',
                'planos' => fn($q) => $q->orderByDesc('aluno_planos.created_at'),
            ])->find($request->query('aluno_id'));

            if ($alunoSelecionado) {
                $historicoPlanos = $alunoSelecionado->planos;
                $pagamentos      = Pagamento::where('aluno_id', $alunoSelecionado->id)->latest()->get();
            } else {
                return redirect()->route('alunos.planos.vincular', array_filter(['busca' => $busca]))
                    ->with('error', 'Aluno não encontrado.');
            }
        }

        $planos = PlanoAluno::orderBy('nome')->get();

        return view('admin.aluno.planos.vincular', compact(
            'planos', 'alunos', 'alunoSelecionado', 'historicoPlanos', 'pagamentos', 'busca'
        ));
    }

    public function vincularStore(Request $request)
    {
        $validated = $request->validate([
            'aluno_id'        => 'required|exists:alunos,id',
            'plano_id'        => 'required|exists:planos_alunos,id',
            'data_inicio'     => 'nullable|date',
            'data_fim'        => 'nullable|date|after_or_equal:data_inicio',
            'status'          => 'required|in:ativo,inativo,cancelado',
            'valor_pago'      => 'nullable|numeric',
            'forma_pagamento' => 'nullable|string|max:255',
        ]);

        Alunos::findOrFail($validated['aluno_id'])->planos()->syncWithoutDetaching([
            $validated['plano_id'] => [
                'data_inicio'     => $validated['data_inicio'] ?? null,
                'data_fim'        => $validated['data_fim'] ?? null,
                'status'          => $validated['status'],
                'valor_pago'      => $validated['valor_pago'] ?? null,
                'forma_pagamento' => $validated['forma_pagamento'] ?? null,
            ],
        ]);

        return redirect()->route('alunos.planos.vincular', ['aluno_id' => $validated['aluno_id']])
            ->with('success', 'Plano vinculado ao aluno com sucesso!');
    }
}
