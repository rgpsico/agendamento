<?php

namespace App\Http\Controllers;

use App\Models\EmpresaSite;
use App\Models\SiteArtigo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteArtigoController extends Controller
{
    public function index(): View
    {
        $site = $this->getEmpresaSite();

        $artigos = SiteArtigo::where('site_id', $site->id)
            ->latest()
            ->paginate(10);

        return view('admin.site.artigos.index', [
            'artigos' => $artigos,
        ]);
    }

    public function create(): View
    {
        return view('admin.site.artigos.create', [
            'statuses' => SiteArtigo::statusOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $site = $this->getEmpresaSite();

        $dados = $this->validateData($request);
        $dados['site_id'] = $site->id;

        if ($request->hasFile('imagem_capa')) {
            $dados['imagem_capa'] = $request->file('imagem_capa')->store('sites/artigos', 'public');
        }

        SiteArtigo::create($dados);

        return redirect()
            ->route('admin.site.artigos.index')
            ->with('success', 'Artigo criado com sucesso!');
    }

    public function edit(SiteArtigo $artigo): View
    {
        $this->authorizeArtigo($artigo);

        return view('admin.site.artigos.edit', [
            'artigo' => $artigo,
            'statuses' => SiteArtigo::statusOptions(),
        ]);
    }

    public function update(Request $request, SiteArtigo $artigo): RedirectResponse
    {
        $this->authorizeArtigo($artigo);

        $dados = $this->validateData($request);

        if ($request->hasFile('imagem_capa')) {
            if ($artigo->imagem_capa) {
                Storage::disk('public')->delete($artigo->imagem_capa);
            }

            $dados['imagem_capa'] = $request->file('imagem_capa')->store('sites/artigos', 'public');
        }

        $artigo->update($dados);

        return redirect()
            ->route('admin.site.artigos.index')
            ->with('success', 'Artigo atualizado com sucesso!');
    }

    public function destroy(SiteArtigo $artigo): RedirectResponse
    {
        $this->authorizeArtigo($artigo);

        if ($artigo->imagem_capa) {
            Storage::disk('public')->delete($artigo->imagem_capa);
        }

        $artigo->delete();

        return redirect()
            ->route('admin.site.artigos.index')
            ->with('success', 'Artigo removido com sucesso!');
    }

    private function getEmpresaSite(): EmpresaSite
    {
        $usuario = Auth::user();

        if (!$usuario || !$usuario->empresa || !$usuario->empresa->site) {
            abort(403, 'Nenhum site configurado para a empresa.');
        }

        return $usuario->empresa->site;
    }

    private function authorizeArtigo(SiteArtigo $artigo): void
    {
        $site = $this->getEmpresaSite();

        abort_if($artigo->site_id !== $site->id, 403, 'Você não tem permissão para acessar este artigo.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'titulo' => 'required|string|max:255',
            'resumo' => 'nullable|string|max:500',
            'conteudo' => 'required|string',
            'status' => 'required|in:' . implode(',', array_keys(SiteArtigo::statusOptions())),
            'imagem_capa' => 'nullable|image|max:2048',
        ]);
    }
}
