<?php

namespace App\Http\Controllers;

use App\Models\NichoConfiguracao;
use App\Models\SistemaConteudo;
use Illuminate\Http\Request;

class ConteudoPublicController extends Controller
{
    /**
     * Listagem pública de artigos — /artigos
     */
    public function index(Request $request)
    {
        // Detecta nicho pelo domínio (via middleware DetectTenant / currentNicho compartilhado)
        $nichoAtual = app()->has('currentNicho') ? app('currentNicho') : null;
        $nicho      = $nichoAtual?->nicho;

        $artigos = SistemaConteudo::publicados()
            ->where('formato', 'artigo')        // só artigos no blog
            ->doNicho($nicho)
            ->latest()
            ->paginate(12);

        return view('public.artigos.index', compact('artigos', 'nichoAtual'));
    }

    /**
     * Página pública de um artigo — /artigos/{slug}
     */
    public function show(string $slug)
    {
        $conteudo = SistemaConteudo::where('slug', $slug)
            ->where('status', 'publicado')
            ->firstOrFail();

        $nichoAtual = app()->has('currentNicho') ? app('currentNicho') : null;

        // Artigos relacionados (mesmo nicho, excluindo o atual)
        $relacionados = SistemaConteudo::publicados()
            ->where('formato', 'artigo')
            ->where('id', '!=', $conteudo->id)
            ->doNicho($conteudo->nicho)
            ->latest()
            ->limit(3)
            ->get();

        return view('public.artigos.show', compact('conteudo', 'nichoAtual', 'relacionados'));
    }
}
