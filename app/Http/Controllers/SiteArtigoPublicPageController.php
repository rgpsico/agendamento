<?php

namespace App\Http\Controllers;

use App\Models\EmpresaSite;
use App\Models\SiteArtigo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteArtigoPublicPageController extends Controller
{
    public function index(Request $request): View
    {
        $site = $this->resolveSite($request);

        $artigos = SiteArtigo::query()
            ->where('status', SiteArtigo::STATUS_PUBLICADO)
            ->when($site, fn ($query) => $query->where('site_id', $site->id))
            ->latest('publicado_em')
            ->paginate(9)
            ->appends($request->query());

        return view('public.blog.index', [
            'artigos' => $artigos,
            'site' => $site,
        ]);
    }

    public function show(Request $request, string $slug): View
    {
        $site = $this->resolveSite($request);

        $artigo = SiteArtigo::query()
            ->where('status', SiteArtigo::STATUS_PUBLICADO)
            ->where('slug', $slug)
            ->when($site, fn ($query) => $query->where('site_id', $site->id))
            ->firstOrFail();

        return view('public.blog.show', [
            'artigo' => $artigo,
            'site' => $site,
        ]);
    }

    private function resolveSite(Request $request): ?EmpresaSite
    {
        $host = $request->getHost();
        $dominioPrincipal = 'agendamento.rjpasseios.com.br';

        if ($host && $host !== $dominioPrincipal) {
            $site = EmpresaSite::where('dominio_personalizado', $host)->first();
            if ($site) {
                return $site;
            }
        }

        if ($request->filled('site_id')) {
            return EmpresaSite::find($request->integer('site_id'));
        }

        return null;
    }
}
