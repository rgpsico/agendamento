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
        $categorias = FinanceiroCategoria::all();

        return view('admin.financeiro.receitas_recorrentes.create', compact('categorias'));
    }

    public function store(Request $request) {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
           'frequencia' => 'required|in:DIARIA,SEMANAL,MENSAL,ANUAL',
            'data_inicio' => 'required|date',
            'empresa_id' => 'required|exists:empresa,id',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);

        ReceitaRecorrente::create($request->all());

        return redirect()->route('financeiro.receitas_recorrentes.index')
            ->with('success', 'Receita recorrente criada com sucesso!');
    }

   public function edit(ReceitaRecorrente $receitaRecorrente) {
       $categorias = FinanceiroCategoria::all();
    return view('admin.financeiro.receitas_recorrentes.edit', compact('receitaRecorrente', 'categorias'));
}


    public function update(Request $request, ReceitaRecorrente $receitaRecorrente) {

      
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'frequencia' => 'required|in:DIARIA,SEMANAL,MENSAL,ANUAL',
            'data_inicio' => 'required|date',
        ]);

        $receitaRecorrente->update($request->all());

        return redirect()->route('financeiro.receitas_recorrentes.index')
            ->with('success', 'Receita recorrente atualizada com sucesso!');
    }

    public function destroy(ReceitaRecorrente $receitaRecorrente) {
        $receitaRecorrente->delete();

        return redirect()->route('financeiro.receitas_recorrentes.index')
            ->with('success', 'Receita recorrente excluída com sucesso!');
    }
}
