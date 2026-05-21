<?php

namespace App\Http\Controllers;

use App\Models\AutomacaoSequencia;
use App\Models\EmailTemplate;
use App\Models\Empresa;
use App\Models\Lead;
use App\Models\Modalidade;
use App\Models\NichoConfiguracao;
use App\Services\CRM\AutomacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Cards de resumo por modalidade
        $modalidades = Modalidade::withCount('empresas')
            ->with(['empresas' => function ($q) {
                $q->where('status', 'ativo');
            }])
            ->get()
            ->map(function ($m) {
                return [
                    'id'       => $m->id,
                    'nome'     => $m->nome,
                    'total'    => $m->empresas_count,
                    'ativos'   => $m->empresas->count(),
                ];
            });

        // Totais gerais
        $totais = [
            'clientes'  => Empresa::count(),
            'ativos'    => Empresa::where('status', 'ativo')->count(),
            'vencidos'  => Empresa::where('data_vencimento', '<', now())->count(),
        ];

        // Últimos 10 cadastros
        $recentes = Empresa::with(['modalidade', 'user', 'site'])
            ->latest()
            ->limit(10)
            ->get();

        return view('super_admin.index', compact('modalidades', 'totais', 'recentes'));
    }

    public function clientes(Request $request)
    {
        $query = Empresa::with(['modalidade', 'user', 'site'])
            ->withCount(['professores', 'servicos']);

        // Filtros
        if ($request->filled('modalidade')) {
            $query->where('modalidade_id', $request->modalidade);
        }

        if ($request->filled('status')) {
            if ($request->status === 'vencido') {
                $query->where('data_vencimento', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%')
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', '%' . $request->busca . '%'));
            });
        }

        $clientes  = $query->orderBy('nome')->paginate(20)->withQueryString();
        $modalidades = Modalidade::orderBy('nome')->get();

        return view('super_admin.clientes', compact('clientes', 'modalidades'));
    }

    public function show(Empresa $empresa)
    {
        $empresa->load([
            'modalidade',
            'user',
            'site',
            'endereco',
            'professores',
            'servicos',
            'paymentGateways',
        ]);

        return view('super_admin.show', compact('empresa'));
    }

    // ─── Nicho Configurações ──────────────────────────────────

    public function nichos()
    {
        $nichos = NichoConfiguracao::orderBy('nome')->get();
        return view('super_admin.nichos.index', compact('nichos'));
    }

    public function nichoCreate()
    {
        return view('super_admin.nichos.form', ['nicho' => new NichoConfiguracao()]);
    }

    public function nichoStore(Request $request)
    {
        $data = $request->validate([
            'nicho'          => 'required|string|max:50|unique:nicho_configuracoes,nicho',
            'nome'           => 'required|string|max:100',
            'dominio'        => 'nullable|string|max:150',
            'dominio_www'    => 'nullable|string|max:150',
            'emoji'          => 'nullable|string|max:10',
            'cor_primaria'   => 'required|string|max:10',
            'cor_secundaria' => 'required|string|max:10',
            'logo'           => 'nullable|image|max:2048',
            'login_imagem'   => 'nullable|image|max:4096',
            'registro_imagem'=> 'nullable|image|max:4096',
            'ativo'          => 'boolean',
        ]);

        foreach (['logo', 'login_imagem', 'registro_imagem'] as $campo) {
            if ($request->hasFile($campo)) {
                $data[$campo] = $request->file($campo)->store("nicho/{$data['nicho']}", 'public');
            } else {
                unset($data[$campo]);
            }
        }

        $data['ativo'] = $request->boolean('ativo', true);

        NichoConfiguracao::create($data);

        return redirect()->route('super.admin.nichos')->with('success', 'Nicho criado com sucesso!');
    }

    public function nichoEdit(NichoConfiguracao $nicho)
    {
        $modalidades = Modalidade::doNicho($nicho->nicho)->orderBy('nome')->get();
        return view('super_admin.nichos.form', compact('nicho', 'modalidades'));
    }

    public function nichoUpdate(Request $request, NichoConfiguracao $nicho)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:100',
            'dominio'        => 'nullable|string|max:150',
            'dominio_www'    => 'nullable|string|max:150',
            'emoji'          => 'nullable|string|max:10',
            'cor_primaria'   => 'required|string|max:10',
            'cor_secundaria' => 'required|string|max:10',
            'logo'           => 'nullable|image|max:2048',
            'login_imagem'   => 'nullable|image|max:4096',
            'registro_imagem'=> 'nullable|image|max:4096',
            'ativo'          => 'boolean',
        ]);

        foreach (['logo', 'login_imagem', 'registro_imagem'] as $campo) {
            if ($request->hasFile($campo)) {
                if ($nicho->$campo) Storage::disk('public')->delete($nicho->$campo);
                $data[$campo] = $request->file($campo)->store("nicho/{$nicho->nicho}", 'public');
            } else {
                unset($data[$campo]);
            }
        }

        $data['ativo'] = $request->boolean('ativo', true);

        $nicho->update($data);

        return redirect()->route('super.admin.nichos')->with('success', 'Nicho atualizado!');
    }

    public function nichoModalidadeStore(Request $request, NichoConfiguracao $nicho)
    {
        $data = $request->validate([
            'nome' => [
                'required', 'string', 'max:100',
                Rule::unique('modalidade')->where('nicho', $nicho->nicho),
            ],
        ]);

        Modalidade::create([
            'nome'  => $data['nome'],
            'nicho' => $nicho->nicho,
        ]);

        return back()->with('success', "Modalidade \"{$data['nome']}\" adicionada ao nicho {$nicho->nome}.");
    }

    public function nichoModalidadeDestroy(NichoConfiguracao $nicho, Modalidade $modalidade)
    {
        abort_unless($modalidade->nicho === $nicho->nicho, 403);

        // Verifica se há empresas usando esta modalidade
        $emUso = $modalidade->empresas()->count();
        if ($emUso > 0) {
            return back()->withErrors(['modalidade' => "Não é possível remover: {$emUso} empresa(s) usam esta modalidade."]);
        }

        $modalidade->delete();

        return back()->with('success', "Modalidade \"{$modalidade->nome}\" removida.");
    }

    public function nichoDestroy(NichoConfiguracao $nicho)
    {
        foreach (['logo', 'login_imagem', 'registro_imagem'] as $campo) {
            if ($nicho->$campo) Storage::disk('public')->delete($nicho->$campo);
        }
        $nicho->delete();

        return redirect()->route('super.admin.nichos')->with('success', 'Nicho removido.');
    }

    // ─── CRM Super Admin ──────────────────────────────────────

    public function crmLeads(Request $request)
    {
        $query = Lead::whereNull('tenant_id')->with('responsavel');

        if ($request->filled('nicho')) {
            $query->where('origem', $request->nicho);
        }

        if ($request->filled('pipeline_status')) {
            $query->where('pipeline_status', $request->pipeline_status);
        }

        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%')
                  ->orWhere('email', 'like', '%' . $request->busca . '%')
                  ->orWhere('telefone', 'like', '%' . $request->busca . '%');
            });
        }

        $leads  = $query->latest()->paginate(25)->withQueryString();
        $totais = Lead::whereNull('tenant_id')
            ->selectRaw('origem, count(*) as total')
            ->groupBy('origem')
            ->pluck('total', 'origem');

        $templates  = EmailTemplate::whereNull('tenant_id')->where('ativo', true)->orderBy('nome')->get();
        $sequencias = AutomacaoSequencia::whereNull('tenant_id')->where('ativo', true)->orderBy('nome')->get();

        return view('super_admin.crm.leads', compact('leads', 'totais', 'templates', 'sequencias'));
    }

    public function crmPipeline(Request $request)
    {
        $nicho = $request->get('nicho', 'todos');

        $query = Lead::whereNull('tenant_id');
        if ($nicho !== 'todos') {
            $query->where('origem', $nicho);
        }

        $colunas = Lead::$pipelineStatus;
        $pipeline = [];
        foreach ($colunas as $status => $label) {
            $pipeline[$status] = (clone $query)
                ->where('pipeline_status', $status)
                ->latest()
                ->get();
        }

        return view('super_admin.crm.pipeline', compact('pipeline', 'colunas', 'nicho'));
    }

    public function crmMover(Request $request, Lead $lead, AutomacaoService $automacao)
    {
        $request->validate(['pipeline_status' => 'required|in:' . implode(',', array_keys(Lead::$pipelineStatus))]);
        abort_unless(is_null($lead->tenant_id), 403);

        $statusAnterior = $lead->pipeline_status;
        $lead->update(['pipeline_status' => $request->pipeline_status]);

        // Dispara automações do super admin
        $automacao->aoMoverLead($lead, $statusAnterior, $request->pipeline_status);

        return back()->with('success', 'Lead movido.');
    }

    // ─── CRM Super Admin — Sequências ─────────────────────────────────────────

    public function crmSequencias(Request $request)
    {
        $nicho     = $request->get('nicho');
        $sequencias = AutomacaoSequencia::forSuperAdmin($nicho)
            ->withCount(['envios as enviados_count' => fn($q) => $q->where('status', 'enviado')])
            ->withCount(['envios as pendentes_count' => fn($q) => $q->where('status', 'pendente')])
            ->with('etapas')
            ->latest()
            ->get();

        $nichos    = NichoConfiguracao::orderBy('nome')->get();
        $templates = EmailTemplate::forSuperAdmin($nicho)->where('ativo', true)->get();

        return view('super_admin.crm.sequencias', compact('sequencias', 'nichos', 'nicho', 'templates'));
    }

    public function crmSequenciaStore(Request $request)
    {
        $validated = $request->validate([
            'nome'                       => 'required|string|max:255',
            'descricao'                  => 'nullable|string|max:1000',
            'nicho'                      => 'nullable|string|max:50',
            'gatilho'                    => 'required|in:manual,pipeline_status,novo_lead',
            'gatilho_valor'              => 'nullable|string|max:100',
            'etapas'                     => 'required|array|min:1',
            'etapas.*.canal'             => 'required|in:whatsapp,email,ambos',
            'etapas.*.delay_dias'        => 'required|integer|min:0|max:365',
            'etapas.*.delay_horas'       => 'required|integer|min:0|max:23',
            'etapas.*.tipo_mensagem'     => 'required|in:ia,template',
            'etapas.*.instrucao_ia'      => 'nullable|string|max:1000',
            'etapas.*.template_mensagem' => 'nullable|string|max:2000',
            'etapas.*.assunto_email'     => 'nullable|string|max:255',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $sequencia = AutomacaoSequencia::create([
                'tenant_id'     => null,
                'nicho'         => $validated['nicho'] ?: null,
                'nome'          => $validated['nome'],
                'descricao'     => $validated['descricao'],
                'gatilho'       => $validated['gatilho'],
                'gatilho_valor' => $validated['gatilho_valor'],
                'ativo'         => true,
            ]);

            foreach ($validated['etapas'] as $ordem => $etapa) {
                $sequencia->etapas()->create([
                    'ordem'              => $ordem + 1,
                    'canal'              => $etapa['canal'],
                    'delay_dias'         => (int) $etapa['delay_dias'],
                    'delay_horas'        => (int) $etapa['delay_horas'],
                    'tipo_mensagem'      => $etapa['tipo_mensagem'],
                    'instrucao_ia'       => $etapa['instrucao_ia'] ?? null,
                    'template_mensagem'  => $etapa['template_mensagem'] ?? null,
                    'assunto_email'      => $etapa['assunto_email'] ?? null,
                ]);
            }
        });

        return redirect()->route('super.admin.crm.sequencias')->with('success', 'Sequência criada!');
    }

    public function crmSequenciaToggle(AutomacaoSequencia $sequencia)
    {
        abort_unless(is_null($sequencia->tenant_id), 403);
        $sequencia->update(['ativo' => !$sequencia->ativo]);
        return back()->with('success', 'Sequência ' . ($sequencia->ativo ? 'ativada' : 'pausada') . '.');
    }

    public function crmSequenciaDestroy(AutomacaoSequencia $sequencia)
    {
        abort_unless(is_null($sequencia->tenant_id), 403);
        $sequencia->delete();
        return redirect()->route('super.admin.crm.sequencias')->with('success', 'Sequência removida.');
    }

    public function crmDispararParaLead(Request $request, AutomacaoSequencia $sequencia, AutomacaoService $automacao)
    {
        abort_unless(is_null($sequencia->tenant_id), 403);
        $request->validate(['lead_id' => 'required|integer|exists:leads,id']);

        $lead = Lead::findOrFail($request->lead_id);
        abort_unless(is_null($lead->tenant_id), 403);

        $sequencia->load('etapas');
        $automacao->iniciarSequencia($lead, $sequencia);

        return back()->with('success', "Sequência disparada para {$lead->nome}!");
    }

    // ─── CRM Super Admin — Templates de Email ────────────────────────────────

    public function crmTemplates(Request $request)
    {
        $nicho     = $request->get('nicho');
        $templates = EmailTemplate::forSuperAdmin($nicho)->latest()->get();
        $nichos    = NichoConfiguracao::orderBy('nome')->get();

        return view('super_admin.crm.templates', compact('templates', 'nichos', 'nicho'));
    }

    public function crmTemplateStore(Request $request)
    {
        $validated = $request->validate([
            'nome'    => 'required|string|max:255',
            'nicho'   => 'nullable|string|max:50',
            'assunto' => 'required|string|max:255',
            'corpo'   => 'required|string',
        ]);

        EmailTemplate::create([
            'tenant_id' => null,
            'nicho'     => $validated['nicho'] ?: null,
            'nome'      => $validated['nome'],
            'assunto'   => $validated['assunto'],
            'corpo'     => $validated['corpo'],
            'ativo'     => true,
        ]);

        return redirect()->route('super.admin.crm.templates')->with('success', 'Template criado!');
    }

    public function crmTemplateUpdate(Request $request, EmailTemplate $template)
    {
        abort_unless(is_null($template->tenant_id), 403);

        $validated = $request->validate([
            'nome'    => 'required|string|max:255',
            'nicho'   => 'nullable|string|max:50',
            'assunto' => 'required|string|max:255',
            'corpo'   => 'required|string',
            'ativo'   => 'boolean',
        ]);

        $template->update([
            'nicho'   => $validated['nicho'] ?: null,
            'nome'    => $validated['nome'],
            'assunto' => $validated['assunto'],
            'corpo'   => $validated['corpo'],
            'ativo'   => $request->boolean('ativo'),
        ]);

        return redirect()->route('super.admin.crm.templates')->with('success', 'Template atualizado!');
    }

    public function crmTemplateGerarIA(Request $request)
    {
        $request->validate([
            'instrucao' => 'required|string|max:1000',
            'nicho'     => 'nullable|string|max:50',
            'tom'       => 'nullable|string|max:50',
        ]);

        $nicho = $request->nicho ? ucfirst($request->nicho) : 'SaaS de gestão';
        $tom   = $request->tom ?: 'profissional e amigável';

        $prompt = <<<EOT
Você é um especialista em email marketing para SaaS. Crie um template de email em português para um sistema de gestão chamado "{$nicho} Gestão".

Tom desejado: {$tom}

Instrução do usuário: {$request->instrucao}

Variáveis disponíveis para personalização (use se fizer sentido):
- {nome} → nome do lead
- {email} → email do lead
- {telefone} → telefone do lead
- {empresa} → empresa do lead
- {interesse} → interesse demonstrado

Retorne APENAS um JSON válido com exatamente este formato:
{
  "assunto": "assunto do email aqui",
  "corpo": "corpo completo do email aqui, pode ter quebras de linha"
}

Não inclua nenhum texto fora do JSON. O corpo pode ter até 500 palavras. Use linguagem natural, não use HTML.
EOT;

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . env('DEEP_SEEK_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post('https://api.deepseek.com/v1/chat/completions', [
                'model'       => 'deepseek-chat',
                'messages'    => [
                    ['role' => 'system', 'content' => 'Você é um especialista em copywriting para email marketing de SaaS. Responda APENAS com JSON válido, sem markdown, sem explicações.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
                'temperature' => 0.8,
                'max_tokens'  => 1500,
            ]);

            if (!$response->successful()) {
                return response()->json(['error' => 'Erro na API DeepSeek: ' . $response->status()], 500);
            }

            $content = $response->json('choices.0.message.content', '');

            // Remove markdown se a IA colocou ```json ... ```
            $content = preg_replace('/^```(?:json)?\s*/i', '', trim($content));
            $content = preg_replace('/\s*```$/', '', $content);

            $decoded = json_decode($content, true);

            if (!$decoded || !isset($decoded['assunto'], $decoded['corpo'])) {
                return response()->json(['error' => 'Resposta da IA em formato inesperado. Tente novamente.'], 422);
            }

            return response()->json([
                'assunto' => $decoded['assunto'],
                'corpo'   => $decoded['corpo'],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao conectar com DeepSeek: ' . $e->getMessage()], 500);
        }
    }

    public function crmTemplateDestroy(EmailTemplate $template)
    {
        abort_unless(is_null($template->tenant_id), 403);
        $template->delete();
        return redirect()->route('super.admin.crm.templates')->with('success', 'Template removido.');
    }

    public function crmBulkEmail(Request $request)
    {
        $request->validate([
            'lead_ids'          => 'required|array|min:1',
            'lead_ids.*'        => 'integer|exists:leads,id',
            'email_template_id' => 'required|integer',
        ]);

        $template = EmailTemplate::whereNull('tenant_id')
            ->where('ativo', true)
            ->findOrFail($request->email_template_id);

        $leads = Lead::whereIn('id', $request->lead_ids)
            ->whereNull('tenant_id')
            ->whereNotNull('email')
            ->get();

        if ($leads->isEmpty()) {
            return back()->with('error', 'Nenhum lead selecionado possui e-mail cadastrado.');
        }

        $enviados = 0;
        foreach ($leads as $lead) {
            try {
                Mail::to($lead->email)->queue(new \App\Mail\LeadTemplateMail($lead, $template));
                $lead->update(['email_enviado_em' => now()]);
                $enviados++;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Bulk email falhou para lead {$lead->id}: " . $e->getMessage());
            }
        }

        return back()->with('success', "E-mail enfileirado para {$enviados} lead(s).");
    }

    public function crmBulkSequencia(Request $request, AutomacaoService $automacao)
    {
        $request->validate([
            'lead_ids'      => 'required|array|min:1',
            'lead_ids.*'    => 'integer|exists:leads,id',
            'sequencia_id'  => 'required|integer',
        ]);

        $sequencia = AutomacaoSequencia::whereNull('tenant_id')
            ->where('ativo', true)
            ->with('etapas')
            ->findOrFail($request->sequencia_id);

        $leads = Lead::whereIn('id', $request->lead_ids)
            ->whereNull('tenant_id')
            ->get();

        if ($leads->isEmpty()) {
            return back()->with('error', 'Nenhum lead selecionado.');
        }

        $disparados = 0;
        foreach ($leads as $lead) {
            $automacao->iniciarSequencia($lead, $sequencia);
            $disparados++;
        }

        return back()->with('success', "Sequência \"{$sequencia->nome}\" disparada para {$disparados} lead(s).");
    }

    public function crmEnviarEmail(Request $request, Lead $lead)
    {
        abort_unless(is_null($lead->tenant_id), 403);
        abort_unless($lead->email, 422);

        $request->validate(['email_template_id' => 'required|integer']);

        $template = EmailTemplate::whereNull('tenant_id')
            ->where('ativo', true)
            ->findOrFail($request->email_template_id);

        try {
            Mail::to($lead->email)->queue(new \App\Mail\LeadTemplateMail($lead, $template));
            $lead->update(['email_enviado_em' => now()]);
            return back()->with('success', "E-mail enviado para {$lead->nome}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Empresa $empresa)
    {
        $empresa->status = $empresa->status === 'ativo' ? 'inativo' : 'ativo';
        $empresa->save();

        return back()->with('success', "Status da empresa '{$empresa->nome}' atualizado.");
    }
}
