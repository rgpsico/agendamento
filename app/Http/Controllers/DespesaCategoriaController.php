<?php 


namespace App\Http\Controllers;

use App\Models\DespesaCategoria;
use Illuminate\Http\Request;

class DespesaCategoriaController extends Controller
{
    public function index()
    {
        $categorias = DespesaCategoria::all();
        return view('admin.financeiro.despesas_categoria.index', compact('categorias'));
    }

    public function create()
    {
        return view('admin.financeiro.despesas_categoria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        DespesaCategoria::create($request->all());

        return redirect()->route('despesas_categorias.index')
                         ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(DespesaCategoria $despesas_categoria)
    {
        return view('admin.financeiro.despesas_categoria.edit', [
            'despesaCategoria' => $despesas_categoria
        ]);
    }


  public function update(Request $request, DespesaCategoria $despesas_categoria)
{
    $request->validate([
        'nome' => 'required|string|max:255',
    ]);

    $despesas_categoria->update($request->only(['nome', 'descricao']));

    return redirect()->route('despesas_categorias.index')
                     ->with('success', 'Categoria atualizada com sucesso!');
}
    public function destroy(DespesaCategoria $despesaCategoria)
    {
        $despesaCategoria->delete();
        return redirect()->route('despesas_categorias.index')
                         ->with('success', 'Categoria removida com sucesso!');
    }
}
