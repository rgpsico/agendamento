<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteArtigo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteArtigoPublicController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $artigos = SiteArtigo::query()
            ->when(
                $request->filled('site_id'),
                fn ($query) => $query->where('site_id', $request->integer('site_id'))
            )
            ->latest()
            ->get();

        return response()->json($artigos);
    }

    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'site_id' => 'required|exists:empresa_site,id',
            'titulo' => 'required|string|max:255',
            'resumo' => 'nullable|string|max:500',
            'conteudo' => 'required|string',
            'status' => 'nullable|in:rascunho,publicado',
            'imagem_capa' => 'nullable|string',
        ]);

        $dados['status'] = $dados['status'] ?? SiteArtigo::STATUS_RASCUNHO;

        $artigo = SiteArtigo::create($dados);

        return response()->json($artigo, 201);
    }
}
