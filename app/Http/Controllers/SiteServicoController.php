<?php

namespace App\Http\Controllers;

use App\Models\SiteServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'titulo'     => 'required|string|max:255',
            'descricao'  => 'required|string',
            'preco'      => 'nullable|numeric',
            'imagem'     => 'nullable|image',
            'nivel'      => 'nullable|string|max:60',
            'duracao'    => 'nullable|string|max:60',
            'capacidade' => 'nullable|string|max:80',
            'info_extra' => 'nullable|string|max:100',
            'destaque'   => 'nullable|boolean',
        ]);

        $empresaSite = Auth::user()->empresa->site;

        $dados = $request->only(['titulo', 'descricao', 'preco', 'nivel', 'duracao', 'capacidade', 'info_extra']);
        $dados['site_id']  = $empresaSite->id;
        $dados['destaque'] = $request->boolean('destaque');

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
            'titulo'     => 'required|string|max:255',
            'descricao'  => 'required|string',
            'preco'      => 'nullable|numeric',
            'imagem'     => 'nullable|image',
            'nivel'      => 'nullable|string|max:60',
            'duracao'    => 'nullable|string|max:60',
            'capacidade' => 'nullable|string|max:80',
            'info_extra' => 'nullable|string|max:100',
            'destaque'   => 'nullable|boolean',
        ]);

        $dados = $request->only(['titulo', 'descricao', 'preco', 'nivel', 'duracao', 'capacidade', 'info_extra']);
        $dados['destaque'] = $request->boolean('destaque');

        if ($request->hasFile('imagem')) {
            $dados['imagem'] = $request->file('imagem')->store('sites/servicos', 'public');
        }

        $servico->update($dados);

        return redirect()->route('admin.site.servicos.index')->with('success', 'Serviço atualizado com sucesso!');
    }

     public function destroy(SiteServico $servico)
    {
        // Verifica se existe imagem associada
        if ($servico->imagem && Storage::exists($servico->imagem)) {
            Storage::delete($servico->imagem);
        }

        // Exclui o depoimento
        $servico->delete();

        return redirect()
            ->back()
            ->with('success', 'Depoimento e imagem removidos com sucesso!');
    }
}
