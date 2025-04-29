<?php

namespace App\Http\Controllers;

use App\Models\EmpresaSite;
use App\Models\SiteContato;
use App\Models\SiteDepoimento;
use App\Models\SiteServico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    /**
     * Exibe o site público com base no slug
     */
    public function mostrar($slug)
    {
        $site = EmpresaSite::where('slug', $slug)
            ->with(['servicos', 'depoimentos', 'contatos'])
            ->firstOrFail();

        return view('site.publico', compact('site'));
    }

    /**
     * Página de edição das configurações do site (painel admin)
     */
    public function edit()
    {
        $empresaId = Auth::user()->empresa->id;

        $site = EmpresaSite::firstOrCreate(
            ['empresa_id' => $empresaId],
            [
                'slug' => Str::slug(Auth::user()->empresa->nome_fantasia),
                'titulo' => Auth::user()->empresa->nome_fantasia,
                'descricao' => null,
            ]
        );

        return view('admin.site.configuracoes', compact('site'));
    }

    /**
     * Salva as configurações atualizadas do site
     */
    public function update(Request $request, EmpresaSite $site)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'logo' => 'nullable|image',
            'capa' => 'nullable|image',
            'cores' => 'nullable|array',
        ]);

        $dados = $request->only(['titulo', 'descricao']);

        if ($request->hasFile('logo')) {
            $dados['logo'] = $request->file('logo')->store('sites/logos', 'public');
        }

        if ($request->hasFile('capa')) {
            $dados['capa'] = $request->file('capa')->store('sites/capas', 'public');
        }

        if ($request->has('cores')) {
            $dados['cores'] = json_encode($request->cores);
        }

        $site->update($dados);

        return redirect()->back()->with('success', 'Site atualizado com sucesso!');
    }
}
