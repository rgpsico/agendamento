<?php

namespace App\Http\Controllers;

use App\Models\SiteServico;
use App\Models\SiteServicoFoto;
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

    /** O serviço precisa ser do site da empresa do usuário logado. */
    private function autorizar(SiteServico $servico): void
    {
        $siteId = Auth::user()?->empresa?->site?->id;
        abort_unless($siteId && (int) $servico->site_id === (int) $siteId, 403);
    }

    public function edit(SiteServico $servico)
    {
        $this->autorizar($servico);
        $servico->load('fotos');

        return view('admin.site.servicos.edit', compact('servico'));
    }

    public function update(Request $request, SiteServico $servico)
    {
        $this->autorizar($servico);
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

    /** Envia uma ou várias fotos para o modal do serviço. */
    public function fotosStore(Request $request, SiteServico $servico)
    {
        $this->autorizar($servico);
        $request->validate([
            'fotos'   => 'required|array|max:20',
            'fotos.*' => 'image|max:6144',
        ], [
            'fotos.required' => 'Escolha ao menos uma foto.',
            'fotos.*.image'  => 'Cada arquivo precisa ser uma imagem.',
            'fotos.*.max'    => 'Cada foto pode ter até 6 MB.',
        ]);

        $ordem = (int) $servico->fotos()->max('ordem');
        foreach ($request->file('fotos') as $arquivo) {
            SiteServicoFoto::create([
                'site_servico_id' => $servico->id,
                'caminho'         => $arquivo->store('sites/servicos/fotos', 'public'),
                'ordem'           => ++$ordem,
            ]);
        }

        return back()->with('success', 'Fotos adicionadas.');
    }

    public function fotosDestroy(SiteServicoFoto $foto)
    {
        $this->autorizar($foto->servico);
        // Só apaga o arquivo se ninguém mais usa o mesmo caminho.
        if ($foto->caminho && SiteServicoFoto::where('caminho', $foto->caminho)->where('id', '!=', $foto->id)->doesntExist()) {
            Storage::disk('public')->delete($foto->caminho);
        }
        $foto->delete();

        return back()->with('success', 'Foto removida.');
    }

     public function destroy(SiteServico $servico)
    {
        $this->autorizar($servico);
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
