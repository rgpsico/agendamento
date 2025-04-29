<?php

namespace App\Http\Controllers;

use App\Models\SiteServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiteServicoController extends Controller
{
    public function index()
    {
        $empresaSite = Auth::user()->empresa->site;

        if (!$empresaSite) {
            abort(403, 'Site não encontrado.');
        }

        $servicos = SiteServico::where('site_id', $empresaSite->id)->paginate(10);

        return view('admin.site.servicos.index', compact('servicos'));
    }

    public function create()
    {
        return view('admin.site.servicos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'nullable|numeric',
            'imagem' => 'nullable|image',
        ]);

        $empresaSite = Auth::user()->empresa->site;


        $dados = $request->only(['titulo', 'descricao', 'preco']);
        $dados['site_id'] = $empresaSite->id;

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('sites/servicos', 'public');
        }

        SiteServico::create($dados);

        return redirect()->route('admin.site.servicos.index')->with('success', 'Serviço criado com sucesso!');
    }

    public function edit(SiteServico $servico)
    {
        return view('admin.site.servicos.edit', compact('servico'));
    }

    public function update(Request $request, SiteServico $servico)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'nullable|numeric',
            'imagem' => 'nullable|image',
        ]);

        $dados = $request->only(['titulo', 'descricao', 'preco']);

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('sites/servicos', 'public');
        }

        $servico->update($dados);

        return redirect()->route('admin.site.servicos.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(SiteServico $servico)
    {
        $servico->delete();

        return redirect()->route('admin.site.servicos.index')->with('success', 'Serviço removido com sucesso!');
    }
}
