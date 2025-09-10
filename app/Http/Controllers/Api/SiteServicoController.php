<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            return response()->json(['message' => 'Site não encontrado'], 403);
        }

        $servicos = SiteServico::where('site_id', $empresaSite->id)->paginate(10);

        return response()->json($servicos);
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

        $servico = SiteServico::create($dados);

        return response()->json($servico, 201);
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
            // Deleta a imagem antiga
            if ($servico->imagem && Storage::disk('public')->exists($servico->imagem)) {
                Storage::disk('public')->delete($servico->imagem);
            }
            $dados['imagem'] = $request->file('imagem')->store('sites/servicos', 'public');
        }

        $servico->update($dados);

        return response()->json($servico);
    }

    public function destroy(SiteServico $servico)
    {
        if ($servico->imagem && Storage::disk('public')->exists($servico->imagem)) {
            Storage::disk('public')->delete($servico->imagem);
        }

        $servico->delete();

        return response()->json(['message' => 'Serviço removido com sucesso']);
    }
}
