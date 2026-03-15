<?php

namespace App\Http\Controllers;

use App\Models\SiteDepoimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteDepoimentoController extends Controller
{
    public function index()
    {
        $site = Auth::user()->empresa->site;

        $depoimentos = SiteDepoimento::where('site_id', $site->id)->paginate(10);

        return view('admin.site.depoimentos.index', compact('depoimentos'));
    }

    public function create()
    {
        return view('admin.site.depoimentos.create');
    }

    public function store(Request $request)
    {
        $site = Auth::user()->empresa->site;

        $request->validate([
            'nome' => 'required|string|max:255',
            'comentario' => 'required|string',
            'nota' => 'nullable|integer|min:1|max:5',
            'foto' => 'nullable|image',
        ]);

        $dados = $request->only(['nome', 'comentario', 'nota']);
        $dados['site_id'] = $site->id;

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('sites/depoimentos', 'public');
        }

        SiteDepoimento::create($dados);

        return redirect()->route('admin.site.depoimentos.index')->with('success', 'Depoimento criado com sucesso!');
    }

    public function edit(SiteDepoimento $depoimento)
    {
        return view('admin.site.depoimentos.edit', compact('depoimento'));
    }

    public function update(Request $request, SiteDepoimento $depoimento)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'comentario' => 'required|string',
            'nota' => 'nullable|integer|min:1|max:5',
            'foto' => 'nullable|image',
        ]);

        $dados = $request->only(['nome', 'comentario', 'nota']);

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('sites/depoimentos', 'public');
        }

        $depoimento->update($dados);

        return redirect()->route('admin.site.depoimentos.index')->with('success', 'Depoimento atualizado com sucesso!');
    }

    public function destroy(SiteDepoimento $depoimento)
    {
        // Verifica se existe imagem associada
        if ($depoimento->imagem && Storage::exists($depoimento->imagem)) {
            Storage::delete($depoimento->imagem);
        }

        // Exclui o depoimento
        $depoimento->delete();

        return redirect()
            ->route('admin.site.depoimentos.index')
            ->with('success', 'Depoimento e imagem removidos com sucesso!');
    }
}
