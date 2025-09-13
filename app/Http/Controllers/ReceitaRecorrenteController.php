<?php 

namespace App\Http\Controllers;

use App\Models\DespesaCategoria;
use App\Models\FinanceiroCategoria;
use App\Models\ReceitaRecorrente;
use Illuminate\Http\Request;

class ReceitaRecorrenteController extends Controller
{
    public function index() {
        $receitas = ReceitaRecorrente::all();
        return view('admin.financeiro.receitas_recorrentes.index', compact('receitas'));
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
