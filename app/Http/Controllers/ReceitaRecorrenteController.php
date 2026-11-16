<?php 

namespace App\Http\Controllers;

use App\Models\DespesaCategoria;
use App\Models\FinanceiroCategoria;
use App\Models\ReceitaRecorrente;
use Illuminate\Http\Request;

class ReceitaRecorrenteController extends Controller
{
    public function index()
{
    $query = ReceitaRecorrente::query();

    if ($aluno = request('aluno')) {
        $query->whereHas('usuario', fn($q) => $q->where('nome', 'like', "%{$aluno}%"));
    }

    if ($descricao = request('descricao')) {
        $query->where('descricao', 'like', "%{$descricao}%");
    }

    if ($categoria = request('categoria_id')) {
        $query->where('categoria_id', $categoria);
    }

    if ($status = request('status')) {
        $query->where('status', $status);
    }

    if ($frequencia = request('frequencia')) {
        $query->where('frequencia', $frequencia);
    }

    if ($data_inicio = request('data_inicio')) {
        $query->whereDate('data_inicio', '>=', $data_inicio);
    }

    if ($data_fim = request('data_fim')) {
        $query->whereDate('data_fim', '<=', $data_fim);
    }

    $receitas = $query->get();

      $totalReceitas = $receitas->sum('valor');
    return view('admin.financeiro.receitas_recorrentes.index', compact('receitas', 'totalReceitas'));
}



    public function create() {
        $categorias = FinanceiroCategoria::where('tipo', 'receita')->orderBy('nome')->get();

        return view('admin.financeiro.receitas_recorrentes.create', compact('categorias'));
    }

    public function store(Request $request) {
        $dados = $this->validar($request);
        // usuario_id = quem lançou; empresa_id = preenchido pelo model com a empresa do usuário logado
        $dados['usuario_id'] = auth()->id();

        ReceitaRecorrente::create($dados);

        return redirect()->route('financeiro.receitas_recorrentes.index')
            ->with('success', 'Receita recorrente criada com sucesso!');
    }

    public function edit(ReceitaRecorrente $receitaRecorrente) {
        $categorias = FinanceiroCategoria::where('tipo', 'receita')->orderBy('nome')->get();

        return view('admin.financeiro.receitas_recorrentes.edit', compact('receitaRecorrente', 'categorias'));
    }

    public function update(Request $request, ReceitaRecorrente $receitaRecorrente) {
        // Só os campos do formulário; empresa e autor do lançamento não mudam.
        $receitaRecorrente->update($this->validar($request));

        return redirect()->route('financeiro.receitas_recorrentes.index')
            ->with('success', 'Receita recorrente atualizada com sucesso!');
    }

    private function validar(Request $request): array
    {
        $dados = $request->validate([
            'descricao'    => 'required|string|max:255',
            'valor'        => 'required|numeric|min:0',
            'categoria_id' => 'nullable|exists:financeiro_categorias,id',
            'frequencia'   => 'required|in:DIARIA,SEMANAL,MENSAL,ANUAL',
            'data_inicio'  => 'required|date',
            'data_fim'     => 'nullable|date|after_or_equal:data_inicio',
            'status'       => 'nullable|in:ACTIVE,INACTIVE',
        ]);

        // Categoria só da própria empresa (o model filtra); de outra empresa vira "sem categoria".
        $dados['categoria_id'] = ! empty($dados['categoria_id'])
            ? FinanceiroCategoria::whereKey($dados['categoria_id'])->value('id')
            : null;

        return $dados;
    }

    public function destroy(ReceitaRecorrente $receitaRecorrente) {
        $receitaRecorrente->delete();

        return redirect()->route('financeiro.receitas_recorrentes.index')
            ->with('success', 'Receita recorrente excluída com sucesso!');
    }
}
