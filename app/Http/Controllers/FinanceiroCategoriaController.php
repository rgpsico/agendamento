<?php 

namespace App\Http\Controllers;

use App\Models\FinanceiroCategoria;
use Illuminate\Http\Request;

class FinanceiroCategoriaController extends Controller
{
    public function index()
    {
        $categorias = FinanceiroCategoria::all();
        return view('admin.financeiro.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.financeiro.categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:DESPESA,RECEITA', // identifica se é despesa ou receita
        ]);

        FinanceiroCategoria::create($request->all());

        return redirect()->route('financeiro.categorias.index')
                         ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(FinanceiroCategoria $categoria)
    {
        return view('admin.financeiro.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, FinanceiroCategoria $categoria)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:DESPESA,RECEITA',
        ]);

        $categoria->update($request->only(['nome', 'descricao', 'tipo']));

        return redirect()->route('financeiro.categorias.index')
                         ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(FinanceiroCategoria $categoria)
    {
        $categoria->delete();

        return redirect()->route('financeiro.categorias.index')
                         ->with('success', 'Categoria removida com sucesso!');
    }
}
