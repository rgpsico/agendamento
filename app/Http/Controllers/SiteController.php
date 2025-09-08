<?php

namespace App\Http\Controllers;

use App\Models\EmpresaSite;
use App\Models\SiteContato;
use App\Models\SiteDepoimento;
use App\Models\SiteServico;
use App\Models\TrackingCode;
use App\Models\SiteTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SiteController extends Controller
{

   public function lista(Request $request)
    {
        $empresaId = Auth::user()->empresa->id;

        $dataInicial = $request->data_inicial ? $request->data_inicial . ' 00:00:00' : null;
        $dataFinal = $request->data_final ? $request->data_final . ' 23:59:59' : null;

        $sites = EmpresaSite::where('empresa_id', $empresaId)
            ->withCount([
                'visualizacoes as visualizacoes_count' => function ($query) use ($dataInicial, $dataFinal) {
                    if ($dataInicial && $dataFinal) {
                        $query->whereBetween('created_at', [$dataInicial, $dataFinal]);
                    }
                },
                'cliquesWhatsapp as cliques_whatsapp_count' => function ($query) use ($dataInicial, $dataFinal) {
                    if ($dataInicial && $dataFinal) {
                        $query->whereBetween('created_at', [$dataInicial, $dataFinal]);
                    }
                },
                'visitantes as visitantes_count' => function ($query) use ($dataInicial, $dataFinal) {
                    if ($dataInicial && $dataFinal) {
                        $query->whereBetween('created_at', [$dataInicial, $dataFinal]);
                    }
                },
            ])
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('admin.site.lista.index', compact('sites'));
    }



  public function create()
    {
        // Aqui você pega todos os templates disponíveis
        // Supondo que você tenha um Model Template
        $templates = SiteTemplate::all(); // ou algum filtro específico

        // Retorna a view passando os templates
        return view('admin.site.lista.create', compact('templates'));
    }

  public function editSite($idsite)
{
   
    $site = EmpresaSite::findOrFail($idsite);

    // Verifica se o usuário tem acesso ao site
    if ($site->empresa_id !== Auth::user()->empresa->id) {
        abort(403, 'Acesso não autorizado.');
    }

    // Busca todos os templates
    $templates = SiteTemplate::all();

    // --- lógica de dnsStatus e sslStatus ---
    $ipServidor = request()->server('SERVER_ADDR') ?? '191.252.92.206';

    $dnsStatus = false;
   
    if (!empty($site->dominio_personalizado)) {
        $dnsRecords = dns_get_record($site->dominio_personalizado, DNS_A);
        $ips = collect($dnsRecords)->pluck('ip')->toArray();
        $dnsStatus = in_array($ipServidor, $ips);
    }

    $sslStatus = false;
    
    if (!empty($site->dominio_personalizado)) {
        $stream = @stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $read = @stream_socket_client("ssl://{$site->dominio_personalizado}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream);
        $sslStatus = $read !== false;
    }

    return view('admin.site.lista.edit', compact('site', 'templates', 'dnsStatus', 'sslStatus', 'ipServidor'));
}

private function verificarDnsSsl($dominio)
{
    $ipServidor = request()->server('SERVER_ADDR') ?? '191.252.92.206';

    $dnsStatus = false;
    if (!empty($dominio)) {
        $dnsRecords = dns_get_record($dominio, DNS_A);
        $ips = collect($dnsRecords)->pluck('ip')->toArray();
        $dnsStatus = in_array($ipServidor, $ips);
    }

    $sslStatus = false;
    if (!empty($dominio)) {
        $stream = @stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $read = @stream_socket_client("ssl://{$dominio}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream);
        $sslStatus = $read !== false;
    }

    return compact('dnsStatus', 'sslStatus', 'ipServidor');
}







    /**
     * Exibe o site público com base no slug
     */
    public function mostrar($slug)
    {
        $site = EmpresaSite::where('slug', $slug)
            ->with([
                'servicos',
                'siteServicos',
                'depoimentos',
                'contatos',
                'endereco',
                'empresa',
                'empresa.modalidade',
                'template', // Carrega o template junto
                'trackingCodes' // Carrega os tracking codes ativos
            ])
            ->firstOrFail();
                
           $view_template = $site->template->path_view ?? 'site.publico';
        return view($view_template, compact('site'));
    }



    public function mostrarDominio(Request $request)
    {
        $host = $request->getHost();

        // Domínio padrão do sistema
        $dominioPrincipal = 'agendamento.rjpasseios.com.br';

        if ($host === $dominioPrincipal) {
            return redirect()->route('home.index'); // ou return app(HomeController::class)->index();
        }

        // Caso seja domínio personalizado
        $site = EmpresaSite::where('dominio_personalizado', $host)
            ->with(['servicos', 'depoimentos', 'contatos', 'template'])
            ->firstOrFail();

        $view_template = $site->template->path_view ?? 'site.publico';

        return view($view_template , compact('site'));
    }



    public function edit()
    {
       
        $empresa = Auth::user()->empresa;

        $site = EmpresaSite::firstOrNew(['empresa_id' => $empresa->id]);

        if (!$site->exists) {
            // Gera o slug base
            $baseSlug = Str::slug($empresa->nome);
            $slug = $baseSlug;
            $count = 1;

            // Verifica se já existe um registro com esse slug
            while (EmpresaSite::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $site->slug = $slug;
            $site->titulo = $empresa->nome;
            $site->descricao = $empresa->descricao;
            $site->cores = [
                'primaria' => '#0ea5e9',
                'secundaria' => '#38b2ac'
            ];
            $site->save();
        }

        return view('admin.site.configuracoes', compact('site'));
    }

    private function createUniqueSlug($titulo)
    {
        $slugBase = Str::slug($titulo);
        $slug = $slugBase;
        $count = 1;

        while (EmpresaSite::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $count;
            $count++;
        }

        return $slug;
    }

    


    public function store(Request $request)
{
    $validated = $request->validate([
        'template_id' => 'required|exists:site_templates,id',
        'titulo' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'logo' => 'nullable|image|max:2048',
        'capa' => 'nullable|image|max:2048',
        'cores.primaria' => 'required|string',
        'cores.secundaria' => 'required|string',
        'sobre_titulo' => 'nullable|string|max:255',
        'sobre_descricao' => 'nullable|string',
        'whatsapp' => 'nullable|string|max:20',
        'autoatendimento_ia' => 'boolean',
        'sobre_imagem' => 'nullable|image|max:2048',
        'sobre_itens.*.icone' => 'nullable|string|max:255',
        'sobre_itens.*.titulo' => 'nullable|string|max:255',
        'sobre_itens.*.descricao' => 'nullable|string',
        'servicos.*.titulo' => 'nullable|string|max:255',
        'servicos.*.descricao' => 'nullable|string',
        'servicos.*.preco' => 'nullable|numeric|min:0',
        'servicos.*.imagem' => 'nullable|image|max:2048',
        'depoimentos.*.nome' => 'nullable|string|max:255',
        'depoimentos.*.nota' => 'nullable|integer|min:0|max:5',
        'depoimentos.*.comentario' => 'nullable|string',
        'depoimentos.*.foto' => 'nullable|image|max:2048',
        'tracking_codes.*.name' => 'nullable|string|max:255',
        'tracking_codes.*.provider' => 'nullable|string|max:255',
        'tracking_codes.*.code' => 'nullable|string|max:255',
        'tracking_codes.*.type' => 'nullable|in:analytics,ads,pixel,other',
        'tracking_codes.*.script' => 'nullable|string',
        'tracking_codes.*.status' => 'boolean',
        'dominio_personalizado' => 'nullable|string|max:255',
        'gerar_vhost' => 'boolean',
    ]);

    // Lógica para salvar o site e os dados relacionados
    // Exemplo:

    $slug = Str::slug($validated['titulo']);

    // Ensure the slug is unique (optional, if uniqueness is required)
    $originalSlug = $slug;
    $counter = 1;
    while (EmpresaSite::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $counter++;
    }
    $site = EmpresaSite::create([
        'empresa_id' => Auth::user()->empresa->id,
        'template_id' => $validated['template_id'],
        'titulo' => $validated['titulo'],
        'slug' => $slug, // Add the slug here
        'descricao' => $validated['descricao'],
        'cores' => [
            'primaria' => $validated['cores']['primaria'],
            'secundaria' => $validated['cores']['secundaria'],
        ],
        'sobre_titulo' => $validated['sobre_titulo'],
        'sobre_descricao' => $validated['sobre_descricao'],
        'whatsapp' => $validated['whatsapp'],
        'autoatendimento_ia' => $validated['autoatendimento_ia'] ?? false,
        'dominio_personalizado' => $validated['dominio_personalizado'],
        'gerar_vhost' => $validated['gerar_vhost'] ?? false,
    ]);

    // Salvar arquivos (logo, capa, sobre_imagem, etc.)
    if ($request->hasFile('logo')) {
        $site->logo = $request->file('logo')->store('logos', 'public');
    }
    if ($request->hasFile('capa')) {
        $site->capa = $request->file('capa')->store('capas', 'public');
    }
    if ($request->hasFile('sobre_imagem')) {
        $site->sobre_imagem = $request->file('sobre_imagem')->store('sobre_imagens', 'public');
    }

    // Salvar itens do "Sobre Nós"
    if (!empty($validated['sobre_itens'])) {
        $site->sobre_itens = $validated['sobre_itens'];
    }

    // Salvar serviços
    if ($request->filled('servicos')) {
        foreach ($request->servicos as $servicoInput) {
            $imagemPath = null;
            if (!empty($servicoInput['imagem'])) {
                $imagemPath = $servicoInput['imagem']->store('sites/servicos', 'public');
            }

            if (!empty($servicoInput['id'])) {
                $servico = SiteServico::find($servicoInput['id']);
                if ($servico) {
                    $servico->update([
                        'titulo' => $servicoInput['titulo'],
                        'descricao' => $servicoInput['descricao'] ?? null,
                        'preco' => $servicoInput['preco'] ?? null,
                        'imagem' => $imagemPath ?? $servicoInput['imagem_existente'] ?? $servico->imagem,
                    ]);
                }
            } else {
                SiteServico::create([
                    'site_id' => $site->id,
                    'titulo' => $servicoInput['titulo'],
                    'descricao' => $servicoInput['descricao'] ?? null,
                    'preco' => $servicoInput['preco'] ?? null,
                    'imagem' => $imagemPath ?? null,
                ]);
            }
        }
    }

    // --- Depoimentos ---
    if ($request->filled('depoimentos')) {
        foreach ($request->depoimentos as $depoimentoInput) {
            if (!empty($depoimentoInput['deleted']) && $depoimentoInput['deleted'] == 1 && !empty($depoimentoInput['id'])) {
                $depo = SiteDepoimento::find($depoimentoInput['id']);
                if ($depo) {
                    if ($depo->foto && Storage::disk('public')->exists($depo->foto)) {
                        Storage::disk('public')->delete($depo->foto);
                    }
                    $depo->delete();
                }
                continue;
            }

            $fotoPath = null;
            if (!empty($depoimentoInput['foto'])) {
                $fotoPath = $depoimentoInput['foto']->store('sites/depoimentos', 'public');
            }

            if (!empty($depoimentoInput['id'])) {
                $depo = SiteDepoimento::find($depoimentoInput['id']);
                if ($depo) {
                    $depo->update([
                        'nome' => $depoimentoInput['nome'],
                        'nota' => $depoimentoInput['nota'] ?? null,
                        'comentario' => $depoimentoInput['comentario'] ?? null,
                        'foto' => $fotoPath ?? $depoimentoInput['foto_existente'] ?? $depo->foto,
                    ]);
                }
            } else {
                SiteDepoimento::create([
                    'site_id' => $site->id,
                    'nome' => $depoimentoInput['nome'],
                    'nota' => $depoimentoInput['nota'] ?? null,
                    'comentario' => $depoimentoInput['comentario'] ?? null,
                    'foto' => $fotoPath ?? null,
                ]);
            }
        }
    }

    // --- Tracking Codes ---
    if ($request->filled('tracking_codes')) {
        foreach ($request->tracking_codes as $trackingInput) {
            if (!empty($trackingInput['deleted']) && $trackingInput['deleted'] == 1 && !empty($trackingInput['id'])) {
                $track = TrackingCode::find($trackingInput['id']);
                if ($track) {
                    $track->delete();
                }
                continue;
            }

            if (!empty($trackingInput['id'])) {
                $track = TrackingCode::find($trackingInput['id']);
                if ($track) {
                    $track->update([
                        'name' => $trackingInput['name'],
                        'provider' => $trackingInput['provider'],
                        'code' => $trackingInput['code'],
                        'type' => $trackingInput['type'],
                        'script' => $trackingInput['script'] ?? null,
                        'status' => $trackingInput['status'] ?? true,
                    ]);
                }
            } else {
                TrackingCode::create([
                    'site_id' => $site->id,
                    'name' => $trackingInput['name'],
                    'provider' => $trackingInput['provider'],
                    'code' => $trackingInput['code'],
                    'type' => $trackingInput['type'],
                    'script' => $trackingInput['script'] ?? null,
                    'status' => $trackingInput['status'] ?? true,
                ]);
            }
        }
    }

    $site->save();

    return redirect()->route('admin.site.lista')->with('success', 'Site criado com sucesso!');
}



    /**
     * Salva as configurações atualizadas do site
     */
   public function update(Request $request, EmpresaSite $site)
{
     set_time_limit(900);
    // Validação
    $validated = $request->validate([
        'titulo' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'cores' => 'required|array',
        'cores.primaria' => 'required|string',
        'cores.secundaria' => 'required|string',
        'sobre_titulo' => 'nullable|string|max:255',
        'sobre_descricao' => 'nullable|string',
        'sobre_itens' => 'nullable|array',
        'sobre_itens.*.icone' => 'nullable|string|max:255',
        'sobre_itens.*.titulo' => 'nullable|string|max:255',
        'sobre_itens.*.descricao' => 'nullable|string',
        'whatsapp' => 'nullable|string|max:20',
        'template_id' => 'required|exists:site_templates,id',

        // Novos campos
        'servicos' => 'nullable|array',
        'servicos.*.titulo' => 'required|string|max:255',
        'servicos.*.descricao' => 'nullable|string',
        'servicos.*.preco' => 'nullable|numeric',
        'servicos.*.imagem' => 'nullable|image|max:2048',

        'depoimentos' => 'nullable|array',
        'depoimentos.*.nome' => 'required|string|max:255',
        'depoimentos.*.nota' => 'nullable|numeric|min:0|max:5',
        'depoimentos.*.comentario' => 'nullable|string',
        'depoimentos.*.foto' => 'nullable|image|max:2048',

        // Tracking codes
        'tracking_codes' => 'nullable|array',
        'tracking_codes.*.name' => 'required|string|max:100',
        'tracking_codes.*.provider' => 'required|string|max:50',
        'tracking_codes.*.code' => 'required|string|max:255',
        'tracking_codes.*.type' => 'required|in:analytics,ads,pixel,other',
        'tracking_codes.*.script' => 'nullable|string',
        'tracking_codes.*.status' => 'boolean',
    ]);

   

    // Dados principais do site
    $data = [
        'titulo' => $request->titulo,
        'descricao' => $request->descricao,
        'template_id' => $request->template_id,
        'cores' => [
            'primaria' => $request->input('cores.primaria', '#0ea5e9'),
            'secundaria' => $request->input('cores.secundaria', '#38b2ac'),
        ],
        'sobre_titulo' => $request->sobre_titulo,
        'sobre_descricao' => $request->sobre_descricao,
        'sobre_itens' => $request->input('sobre_itens', []),
        'whatsapp' => $request->whatsapp,
        'autoatendimento_ia' => $request->has('autoatendimento_ia'),
    ];

    // Upload logo
    if ($request->hasFile('logo')) {
        if ($site->logo && Storage::disk('public')->exists($site->logo)) {
            Storage::disk('public')->delete($site->logo);
        }
        $data['logo'] = $request->file('logo')->store('sites/logos', 'public');
    }

    // Upload capa
    if ($request->hasFile('capa')) {
        if ($site->capa && Storage::disk('public')->exists($site->capa)) {
            Storage::disk('public')->delete($site->capa);
        }
        $data['capa'] = $request->file('capa')->store('sites/capas', 'public');
    }

    // Upload sobre_imagem
    if ($request->hasFile('sobre_imagem')) {
        if ($site->sobre_imagem && Storage::disk('public')->exists($site->sobre_imagem)) {
            Storage::disk('public')->delete($site->sobre_imagem);
        }
        $data['sobre_imagem'] = $request->file('sobre_imagem')->store('sites/sobre', 'public');
    }

    // Atualiza dados do site
    $site->update($data);
     $this->atualizarConfiguracoes($request, $site);
    // --- Serviços ---
    if ($request->filled('servicos')) {
        foreach ($request->servicos as $servicoInput) {
            $imagemPath = null;
            if (!empty($servicoInput['imagem'])) {
                $imagemPath = $servicoInput['imagem']->store('sites/servicos', 'public');
            }

            if (!empty($servicoInput['id'])) {
                $servico = SiteServico::find($servicoInput['id']);
                if ($servico) {
                    $servico->update([
                        'titulo' => $servicoInput['titulo'],
                        'descricao' => $servicoInput['descricao'] ?? null,
                        'preco' => $servicoInput['preco'] ?? null,
                        'imagem' => $imagemPath ?? $servicoInput['imagem_existente'] ?? $servico->imagem,
                    ]);
                }
            } else {
                SiteServico::create([
                    'site_id' => $site->id,
                    'titulo' => $servicoInput['titulo'],
                    'descricao' => $servicoInput['descricao'] ?? null,
                    'preco' => $servicoInput['preco'] ?? null,
                    'imagem' => $imagemPath ?? null,
                ]);
            }
        }
    }

    // --- Depoimentos ---
    if ($request->filled('depoimentos')) {
        foreach ($request->depoimentos as $depoimentoInput) {
            if (!empty($depoimentoInput['deleted']) && $depoimentoInput['deleted'] == 1 && !empty($depoimentoInput['id'])) {
                $depo = SiteDepoimento::find($depoimentoInput['id']);
                if ($depo) {
                    if ($depo->foto && Storage::disk('public')->exists($depo->foto)) {
                        Storage::disk('public')->delete($depo->foto);
                    }
                    $depo->delete();
                }
                continue;
            }

            $fotoPath = null;
            if (!empty($depoimentoInput['foto'])) {
                $fotoPath = $depoimentoInput['foto']->store('sites/depoimentos', 'public');
            }

            if (!empty($depoimentoInput['id'])) {
                $depo = SiteDepoimento::find($depoimentoInput['id']);
                if ($depo) {
                    $depo->update([
                        'nome' => $depoimentoInput['nome'],
                        'nota' => $depoimentoInput['nota'] ?? null,
                        'comentario' => $depoimentoInput['comentario'] ?? null,
                        'foto' => $fotoPath ?? $depoimentoInput['foto_existente'] ?? $depo->foto,
                    ]);
                }
            } else {
                SiteDepoimento::create([
                    'site_id' => $site->id,
                    'nome' => $depoimentoInput['nome'],
                    'nota' => $depoimentoInput['nota'] ?? null,
                    'comentario' => $depoimentoInput['comentario'] ?? null,
                    'foto' => $fotoPath ?? null,
                ]);
            }
        }
    }

    // --- Tracking Codes ---
    if ($request->filled('tracking_codes')) {
        foreach ($request->tracking_codes as $trackingInput) {
            if (!empty($trackingInput['deleted']) && $trackingInput['deleted'] == 1 && !empty($trackingInput['id'])) {
                $track = TrackingCode::find($trackingInput['id']);
                if ($track) {
                    $track->delete();
                }
                continue;
            }

            if (!empty($trackingInput['id'])) {
                $track = TrackingCode::find($trackingInput['id']);
                if ($track) {
                    $track->update([
                        'name' => $trackingInput['name'],
                        'provider' => $trackingInput['provider'],
                        'code' => $trackingInput['code'],
                        'type' => $trackingInput['type'],
                        'script' => $trackingInput['script'] ?? null,
                        'status' => $trackingInput['status'] ?? true,
                    ]);
                }
            } else {
                TrackingCode::create([
                    'site_id' => $site->id,
                    'name' => $trackingInput['name'],
                    'provider' => $trackingInput['provider'],
                    'code' => $trackingInput['code'],
                    'type' => $trackingInput['type'],
                    'script' => $trackingInput['script'] ?? null,
                    'status' => $trackingInput['status'] ?? true,
                ]);
            }
        }
    }

    return redirect()->back()->with('success', 'Configurações do site atualizadas com sucesso!');
}



    protected function criarOuAtualizarVirtualHost($dominio)
    {
        // Validar domínio
        if (!filter_var('http://' . $dominio, FILTER_VALIDATE_URL)) {
            throw new \Exception('Domínio inválido.');
        }

        $scriptPath = '/usr/local/bin/criar-vhost.sh';

        $process = new Process(["sudo", $scriptPath, $dominio]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return true;
    }



    public function atualizarConfiguracoes(Request $request, EmpresaSite $site)
    {
        // Validação
     set_time_limit(900);

        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'dominio_personalizado' => 'nullable|string|max:255',
            'gerar_vhost' => 'nullable|boolean',
            'whatsapp' => 'nullable|string',
            'cores' => 'nullable|array',
            'sobre_titulo' => 'nullable|string|max:255',
            'sobre_descricao' => 'nullable|string',
            'sobre_itens' => 'nullable|array',
            'autoatendimento_ia' => 'nullable|boolean',
        ]);


        $site->update($data);

        // Se marcou gerar VHost e domínio válido
        if (!empty($site->dominio_personalizado) && $site->gerar_vhost) {
            try {
                $this->criarOuAtualizarVirtualHost($site->dominio_personalizado);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['erro' => 'Erro ao criar Virtual Host: ' . $e->getMessage()]);
            }
        }

        return redirect()->back()->with('success', 'Configurações atualizadas com sucessoaaa!');
    }

    public function atualizarDominio(Request $request, EmpresaSite $site)
    {
        $request->validate([
            'dominio_personalizado' => 'required|string|max:255',
        ]);

        $dominio = strtolower(trim($request->dominio_personalizado));

        // Atualiza domínio do site específico
        $site->dominio_personalizado = $dominio;
        $site->save();

        // Criar Virtual Host para este site
        try {
            $this->criarVirtualHost($dominio);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['erro' => 'Erro ao criar o virtual host: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Domínio atualizado e virtual host configurado!');
    }


    protected function criarVirtualHost($dominio)
    {
        $scriptPath = '/usr/local/bin/criar-vhost.sh';

        $process = new Process(["sudo", $scriptPath, $dominio]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return true;
    }




    public function editarDominio()
    {
        $site = EmpresaSite::where('empresa_id', Auth::user()->empresa->id)->firstOrFail();
        $ipServidor = request()->server('SERVER_ADDR') ?? '191.252.92.206';

        $dnsStatus = false;
        if (!empty($site->dominio_personalizado)) {
            $dnsRecords = dns_get_record($site->dominio_personalizado, DNS_A);
            $ips = collect($dnsRecords)->pluck('ip')->toArray();
            $dnsStatus = in_array($ipServidor, $ips);
        }

        $sslStatus = false;
        if (!empty($site->dominio_personalizado)) {
            $stream = @stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
            $read = @stream_socket_client("ssl://{$site->dominio_personalizado}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream);
            $sslStatus = $read !== false;
        }



        return view('admin.site.ssl.edit', compact('site', 'dnsStatus', 'sslStatus', 'ipServidor'));
    }



    public function gerarSSL(Request $request)
    {
        // Garante que o site_id foi enviado
        $request->validate([
            'site_id' => 'required|integer|exists:empresa_site,id'
        ]);


   
        // Busca o site pelo ID e verifica se pertence à empresa logada
        $site = EmpresaSite::where('id', $request->site_id)
            ->where('empresa_id', Auth::user()->empresa->id)
            ->first();

        if (!$site) {
            return redirect()->route('admin.site.dominios')
                ->with('error', 'Domínio não encontrado ou não pertence à sua empresa.');
        }

        if (!$site->dominio_personalizado) {
            return back()->with('error', 'Domínio personalizado não configurado.');
        }

        $dominio = $site->dominio_personalizado;
        $script = '/usr/local/bin/gerar-ssl.sh';

        $process = Process::fromShellCommandline("sudo $script $dominio");
        $process->run();

        if (!$process->isSuccessful()) {
            return back()->with('error', 'Erro ao gerar SSL: ' . $process->getErrorOutput());
        }

        return back()->with('success', "SSL para {$dominio} gerado com sucesso!");
    }



    public function destroy($idsite)
    {
        // Find the site or fail
        $site = EmpresaSite::findOrFail($idsite);

        // Check if the user is authorized to delete the site
        if ($site->empresa_id !== Auth::user()->empresa->id) {
            return redirect()->route('admin.site.lista')->with('error', 'Acesso não autorizado.');
        }

        // Delete associated files (logo, capa, sobre_imagem, etc.)
        if ($site->logo) {
            Storage::disk('public')->delete($site->logo);
        }
        if ($site->capa) {
            Storage::disk('public')->delete($site->capa);
        }
        if ($site->sobre_imagem) {
            Storage::disk('public')->delete($site->sobre_imagem);
        }

        // Delete related services and their images
        foreach ($site->siteServicos as $servico) {
            if ($servico->imagem) {
                Storage::disk('public')->delete($servico->imagem);
            }
            $servico->delete();
        }

        // Delete related testimonials and their images
        foreach ($site->depoimentos as $depoimento) {
            if ($depoimento->foto) {
                Storage::disk('public')->delete($depoimento->foto);
            }
            $depoimento->delete();
        }

        // Delete related tracking codes (no files associated)
        $site->trackingCodes()->delete();

        // Delete the site
        $site->delete();

        return redirect()->route('admin.site.lista')->with('success', 'Site excluído com sucesso!');
    }

}
