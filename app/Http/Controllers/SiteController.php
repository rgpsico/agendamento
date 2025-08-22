<?php

namespace App\Http\Controllers;

use App\Models\EmpresaSite;
use App\Models\SiteContato;
use App\Models\SiteDepoimento;
use App\Models\SiteServico;
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

        $sites = EmpresaSite::where('empresa_id', $empresaId)
            ->withCount(['visualizacoes', 'cliquesWhatsapp', 'visitantes']);

        // 🔎 Filtros
        if ($request->filled('titulo')) {
            $sites->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->filled('slug')) {
            $sites->where('slug', 'like', '%' . $request->slug . '%');
        }

        if ($request->filled('data_inicial') && $request->filled('data_final')) {
            $sites->whereBetween('created_at', [$request->data_inicial, $request->data_final]);
        }

        $sites = $sites->latest()->paginate(10)->appends($request->query());

        return view('admin.site.lista.index', compact('sites'));
    }



    public function create()
    {
        return view('admin.site.lista.create');
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
                'template' // Carrega o template junto
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
            'dominio_personalizado' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'template_id' => 'required|exists:site_templates,id',
        ]);

        $data = [
            'empresa_id' => Auth::user()->empresa->id,
            'titulo' => $request->titulo,
            'template_id' => $request->template_id, // novo campo

            'descricao' => $request->descricao,
            'cores' => [
                'primaria' => $request->input('cores.primaria', '#0ea5e9'),
                'secundaria' => $request->input('cores.secundaria', '#38b2ac'),
            ],
            'sobre_titulo' => $request->sobre_titulo,
            'sobre_descricao' => $request->sobre_descricao,
            'sobre_itens' => $request->input('sobre_itens', []),
            'dominio_personalizado' => $request->input('dominio_personalizado'),
            'whatsapp' => $request->whatsapp,
        ];

        $data['slug'] = $this->createUniqueSlug($request->titulo);

        // Upload do logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('sites/logos', 'public');
        }

        // Upload da capa
        if ($request->hasFile('capa')) {
            $data['capa'] = $request->file('capa')->store('sites/capas', 'public');
        }

        // Upload imagem da seção sobre nós
        if ($request->hasFile('sobre_imagem')) {
            $data['sobre_imagem'] = $request->file('sobre_imagem')->store('sites/sobre', 'public');
        }

        $site = EmpresaSite::create($data);

        // Se quiser, pode criar o virtual host aqui futuramente
        // $this->criarVirtualHost($site->dominio_personalizado);

        return redirect()
            ->route('admin.site.lista')
            ->with('success', 'Site criado com sucesso!');
    }



    /**
     * Salva as configurações atualizadas do site
     */
    public function update(Request $request, EmpresaSite $site)
    {
      
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
            'dominio_personalizado' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'template_id' => 'required|exists:site_templates,id',
            
        ]);



        $data = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'template_id' => $request->template_id, // novo campo
            'cores' => [
                'primaria' => $request->input('cores.primaria', '#0ea5e9'),
                'secundaria' => $request->input('cores.secundaria', '#38b2ac'),
            ],
            'sobre_titulo' => $request->sobre_titulo,
            'sobre_descricao' => $request->sobre_descricao,
            'sobre_itens' => $request->input('sobre_itens', []),
            'whatsapp' => $request->whatsapp,
        ];

        // $data['slug'] = $this->createUniqueSlug($request->titulo);

        if (!empty($request->dominio_personalizado) && $request->gerar_vhost) {
            $data['dominio_personalizado'] = $request->dominio_personalizado;

            $site->vhost_criado = true;

            $this->criarVirtualHost($request->dominio_personalizado);
        }

        // Upload do logo


        if ($request->hasFile('logo')) {
            if (!empty($site->logo) && Storage::disk('public')->exists($site->logo)) {
                Storage::disk('public')->delete($site->logo);
            }
            $data['logo'] = $request->file('logo')->store('sites/logos', 'public');
        }

        // Upload da capa
       if ($request->hasFile('capa')) {
            \Log::info('Arquivo capa detectado na requisição.');
            if (!empty($site->capa) && Storage::disk('public')->exists($site->capa)) {
                \Log::info('Deletando capa antiga: ' . $site->capa);
                Storage::disk('public')->delete($site->capa);
            } else {
                \Log::info('Capa antiga não encontrada ou não existe: ' . ($site->capa ?? 'vazio'));
            }
            $data['capa'] = $request->file('capa')->store('sites/capas', 'public');
            \Log::info('Nova capa salva em: ' . $data['capa']);
        } else {
            \Log::error('Nenhum arquivo capa encontrado na requisição.');
        }

        // Upload imagem da seção sobre nós
        if ($request->hasFile('sobre_imagem')) {
            if (!empty($site->sobre_imagem) && Storage::disk('public')->exists($site->sobre_imagem)) {
                Storage::disk('public')->delete($site->sobre_imagem);
            }
            $data['sobre_imagem'] = $request->file('sobre_imagem')->store('sites/sobre', 'public');
        }

        if ($request->filled('dominio')) {
            $data['dominio_personalizado'] = $request->dominio;
        }

        $site->update($data);

        if ($request->filled('dominio_personalizado')) {
            $data['dominio_personalizado'] = $request->dominio;
            //   $this->criarVirtualHost($request->dominio);
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

}
