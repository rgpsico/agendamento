<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteArtigo;
use App\Services\ArtigoTesteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TesteController extends Controller
{
    public function store(Request $request, ArtigoTesteService $service): JsonResponse
    {
        $dados = $request->validate([
            'site_id' => 'required|exists:empresa_site,id',
            'titulo' => 'required|string|max:255',
            'resumo' => 'nullable|string|max:500',
            'conteudo' => 'required|string',
            'status' => 'nullable|in:rascunho,publicado',
            'imagem_capa' => 'nullable|string',
        ]);

        $dados = $service->normalizarDados($dados);

        $artigo = SiteArtigo::create($dados);

        return response()->json($artigo, 201);
    }

    public function exemplo(Request $request, ArtigoTesteService $service): JsonResponse
    {
        $siteId = $request->integer('site_id', 1);

        return response()->json($service->exemploPayload($siteId));
    }
}
