<?php 

namespace App\Http\Controllers;

use App\Models\Plano;
use Illuminate\Http\Request;

class PlanoController extends Controller
{
   public function index()
{
    // Captura o filtro da query string, se houver
    $filtro = request()->query('filtro');

    // Busca planos, aplicando filtro no nome caso exista
    $planos = Plano::when($filtro, function($query, $filtro) {
        $query->where('nome', 'like', "%{$filtro}%");
    })->latest()->get();

    return view('admin.financeiro.planos.index', compact('planos', 'filtro'));
}


    public function create()
    {
        return view('admin.financeiro.planos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'periodicidade' => 'required|in:mensal,trimestral,anual',
            'descricao' => 'nullable|string',
        ]);

        Plano::create($request->all());

        return redirect()->route('admin.planos.index')->with('success', 'Plano criado com sucesso!');
    }

    public function edit(Plano $plano)
    {
        return view('admin.financeiro.planos.edit', compact('plano'));
    }

    public function update(Request $request, Plano $plano)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'periodicidade' => 'required|in:mensal,trimestral,anual',
            'descricao' => 'nullable|string',
        ]);

        $plano->update($request->all());

        return redirect()->route('admin.planos.index')->with('success', 'Plano atualizado com sucesso!');
    }

    public function destroy(Plano $plano)
    {
        $plano->delete();
        return redirect()->route('admin.planos.index')->with('success', 'Plano excluído com sucesso!');
    }
}

