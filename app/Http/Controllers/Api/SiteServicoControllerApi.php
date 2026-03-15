<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteDepoimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiteServicoControllerApi extends Controller
{
    // Listar depoimentos
    public function index()
    {
        $site = Auth::user()->empresa->site;

        $depoimentos = SiteDepoimento::where('site_id', $site->id)->paginate(10);

        return response()->json($depoimentos);
    }

    // Criar depoimento
    public function store(Request $request)
    {
        $site = Auth::user()->empresa->site;

        $request->validate([
            'nome' => 'required|string|max:255',
            'comentario' => 'required|string',
            'nota' => 'nullable|integer|min:0|max:5',
            'foto' => 'nullable|image|max:2048',
        ]);

        $dados = $request->only(['nome', 'comentario', 'nota']);
        $dados['site_id'] = $site->id;

        if ($request->hasFile('foto')) {
            $dados['foto'] = $request->file('foto')->store('sites/depoimentos', 'public');
        }

        $depoimento = SiteDepoimento::create($dados);

        return response()->json([
            'success' => true,
            'message' => 'Depoimento criado com sucesso!',
            'data' => $depoimento
        ], 201);
    }

    // Visualizar um depoimento
    public function show(SiteDepoimento $depoimento)
    {
        return response()->json($depoimento);
    }

    // Atualizar depoimento
    public function update(Request $request, SiteDepoimento $depoimento)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'comentario' => 'required|string',
            'nota' => 'nullable|integer|min:0|max:5',
            'foto' => 'nullable|image|max:2048',
        ]);

        $dados = $request->only(['nome', 'comentario', 'nota']);

        if ($request->hasFile('foto')) {
            if ($depoimento->foto && Storage::disk('public')->exists($depoimento->foto)) {
                Storage::disk('public')->delete($depoimento->foto);
            }
            $dados['foto'] = $request->file('foto')->store('sites/depoimentos', 'public');
        }

        $depoimento->update($dados);

        return response()->json([
            'success' => true,
            'message' => 'Depoimento atualizado com sucesso!',
            'data' => $depoimento
        ]);
    }

    // Remover depoimento
    public function destroy(SiteDepoimento $depoimento)
    {
        if ($depoimento->foto && Storage::disk('public')->exists($depoimento->foto)) {
            Storage::disk('public')->delete($depoimento->foto);
        }

        $depoimento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Depoimento removido com sucesso!'
        ]);
    }
}
