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
        $empresa = Auth::user()->empresa;

        $site = EmpresaSite::firstOrCreate(
            ['empresa_id' => $empresa->id],
            [
                'slug' => Str::slug($empresa->nome_fantasia),
                'titulo' => $empresa->nome_fantasia,
                'descricao' => null,
                'cores' => [
                    'primaria' => '#0ea5e9',
                    'secundaria' => '#38b2ac'
                ],
            ]
        )->refresh(); // força carregar o que realmente está salvo no banco

        return view('admin.site.configuracoes', compact('site'));
    }



    /**
     * Salva as configurações atualizadas do site
     */
    public function update(Request $request, EmpresaSite $site)
    {
        // Remova a linha dd($request->all()) após a depuração

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cores' => 'required|array',
            'cores.primaria' => 'required|string',
            'cores.secundaria' => 'required|string',
            'sobre_titulo' => 'nullable|string|max:255',
            'sobre_descricao' => 'nullable|string',
            'sobre_itens' => 'nullable|string', // validando o JSON como string
        ]);

        $data = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'cores' => [
                'primaria' => $request->input('cores.primaria', $request->input('cores')['primaria'] ?? '#0ea5e9'),
                'secundaria' => $request->input('cores.secundaria', $request->input('cores')['secundaria'] ?? '#38b2ac'),
            ],
            'sobre_titulo' => $request->sobre_titulo,
            'sobre_descricao' => $request->sobre_descricao,
        ];

        // Processamento do JSON dos itens do Sobre Nós
        if ($request->filled('sobre_itens')) {
            try {
                $data['sobre_itens'] = json_decode($request->sobre_itens, true);

                // Verificação adicional - se json_decode falhar, retornará null
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['sobre_itens' => 'O formato JSON dos itens está inválido.']);
                }
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['sobre_itens' => 'Erro ao processar os itens: ' . $e->getMessage()]);
            }
        }

        // Upload do logo
        if ($request->hasFile('logo')) {
            // Remover o arquivo antigo se existir
            if (!empty($site->logo) && Storage::disk('public')->exists($site->logo)) {
                Storage::disk('public')->delete($site->logo);
            }

            $data['logo'] = $request->file('logo')->store('sites/logos', 'public');
        }

        // Upload da capa
        if ($request->hasFile('capa')) {
            // Remover o arquivo antigo se existir
            if (!empty($site->capa) && Storage::disk('public')->exists($site->capa)) {
                Storage::disk('public')->delete($site->capa);
            }

            $data['capa'] = $request->file('capa')->store('sites/capas', 'public');
        }

        // Upload da imagem sobre nós
        if ($request->hasFile('sobre_imagem')) {
            // Remover o arquivo antigo se existir
            if (!empty($site->sobre_imagem) && Storage::disk('public')->exists($site->sobre_imagem)) {
                Storage::disk('public')->delete($site->sobre_imagem);
            }

            $data['sobre_imagem'] = $request->file('sobre_imagem')->store('sites/sobre', 'public');
        }

        $site->update($data);

        return redirect()->back()->with('success', 'Configurações do site atualizadas com sucesso!');
    }
}
