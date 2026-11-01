<?php

namespace App\Http\Controllers;

use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Pagamento;
use App\Models\PlanoAluno;
use App\Models\Professor;
use App\Support\EmpresasDoUsuario;
use Illuminate\Http\Request;

class PlanoAlunoController extends Controller
{
    /** Empresas que o usuario logado pode ver; null = master (todas). */
    private function empresas(): ?array
    {
        $usuario = auth()->user();

        return $usuario->isMasterUser() ? null : EmpresasDoUsuario::ids($usuario);
    }

    /** Empresa dona do plano novo: a informada (ja conferida pelo middleware) ou a primeira do usuario. */
    private function empresaDoNovoPlano(Request $request): int
    {
        $ids = EmpresasDoUsuario::ids(auth()->user());
        $empresaId = $request->filled('empresa_id') ? (int) $request->input('empresa_id') : ($ids[0] ?? null);

        abort_unless($empresaId, 422, 'Cadastre a sua empresa antes de criar planos.');

        return $empresaId;
    }

    /** Alunos que o usuario pode ver: os ligados a professores das suas empresas (master: todos). */
    private function alunosVisiveis()
    {
        $query = Alunos::with('usuario')
            ->whereHas('usuario', fn($q) => $q->whereRaw('LOWER(tipo_usuario) = ?', ['aluno']));

        if (($ids = $this->empresas()) !== null) {
            $professores = Professor::whereIn('empresa_id', $ids ?: [0])->select('id');
            $query->whereIn('alunos.id', AlunoProfessor::whereIn('professor_id', $professores)->select('aluno_id'));
        }

        return $query;
    }

    public function indexView()
    {
        $planos = PlanoAluno::dasEmpresas($this->empresas())->orderBy('nome')->get();
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
                'empresa_id'    => $this->empresaDoNovoPlano($request),
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
                'empresa_id'    => $this->empresaDoNovoPlano($request),
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
        $plano = PlanoAluno::dasEmpresas($this->empresas())->findOrFail($id);
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
        PlanoAluno::dasEmpresas($this->empresas())->findOrFail($id)->delete();
        return redirect()->route('alunos.planos.index')
            ->with('success', 'Plano removido com sucesso!');
    }

    /* ── API endpoints (mantidos para compatibilidade) ─── */

    /** Publico: lista os planos de UMA empresa (?empresa_id=); sem o filtro nao lista nada. */
    public function index(Request $request)
    {
        if (! $request->filled('empresa_id')) {
            return response()->json([]);
        }

        return response()->json(PlanoAluno::where('empresa_id', $request->integer('empresa_id'))->orderBy('nome')->get());
    }

    public function show($id)
    {
        return response()->json(PlanoAluno::findOrFail($id));
    }

    /* ── Vincular aluno a plano ─────────────────────────── */

    public function vincular(Request $request)
    {
        $busca = $request->query('busca');

        $alunosQuery = $this->alunosVisiveis();

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
            $alunoSelecionado = $this->alunosVisiveis()->with([
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

        $planos = PlanoAluno::dasEmpresas($this->empresas())->orderBy('nome')->get();

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

        // aluno e plano precisam ser da(s) empresa(s) do usuario (alem do middleware, confere aqui tambem)
        abort_unless(PlanoAluno::dasEmpresas($this->empresas())->whereKey($validated['plano_id'])->exists(), 403);
        $aluno = $this->alunosVisiveis()->find($validated['aluno_id']);
        abort_unless($aluno, 403);

        $aluno->planos()->syncWithoutDetaching([
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
