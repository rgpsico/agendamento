<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\AutomacaoEnvio;
use App\Models\AutomacaoEtapa;
use App\Models\AutomacaoSequencia;
use App\Models\Lead;
use App\Services\CRM\AutomacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SequenciaController extends Controller
{
    // ─── Listagem ────────────────────────────────────────────────────────────

    public function index()
    {
        $tenantId  = $this->tenantId();
        $sequencias = AutomacaoSequencia::forTenant($tenantId)
            ->withCount(['envios as enviados_count' => fn($q) => $q->where('status', 'enviado')])
            ->withCount(['envios as pendentes_count' => fn($q) => $q->where('status', 'pendente')])
            ->with('etapas')
            ->latest()
            ->get();

        return view('crm.sequencias.index', compact('sequencias'));
    }

    // ─── Criar sequência ─────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'           => 'required|string|max:255',
            'descricao'      => 'nullable|string|max:1000',
            'gatilho'        => 'required|in:manual,pipeline_status,novo_lead',
            'gatilho_valor'  => 'nullable|string|max:100',

            // Etapas
            'etapas'                   => 'required|array|min:1',
            'etapas.*.canal'           => 'required|in:whatsapp,email,ambos',
            'etapas.*.delay_dias'      => 'required|integer|min:0|max:365',
            'etapas.*.delay_horas'     => 'required|integer|min:0|max:23',
            'etapas.*.tipo_mensagem'   => 'required|in:ia,template',
            'etapas.*.instrucao_ia'    => 'nullable|string|max:1000',
            'etapas.*.template_mensagem' => 'nullable|string|max:2000',
            'etapas.*.assunto_email'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $sequencia = AutomacaoSequencia::create([
                'tenant_id'     => $this->tenantId(),
                'nome'          => $validated['nome'],
                'descricao'     => $validated['descricao'],
                'gatilho'       => $validated['gatilho'],
                'gatilho_valor' => $validated['gatilho_valor'],
                'ativo'         => true,
            ]);

            foreach ($validated['etapas'] as $ordem => $etapaDados) {
                $sequencia->etapas()->create([
                    'ordem'              => $ordem + 1,
                    'canal'              => $etapaDados['canal'],
                    'delay_dias'         => (int) $etapaDados['delay_dias'],
                    'delay_horas'        => (int) $etapaDados['delay_horas'],
                    'tipo_mensagem'      => $etapaDados['tipo_mensagem'],
                    'instrucao_ia'       => $etapaDados['instrucao_ia'] ?? null,
                    'template_mensagem'  => $etapaDados['template_mensagem'] ?? null,
                    'assunto_email'      => $etapaDados['assunto_email'] ?? null,
                ]);
            }
        });

        return redirect()->route('crm.sequencias.index')
            ->with('success', 'Sequência criada com sucesso!');
    }

    // ─── Editar sequência ────────────────────────────────────────────────────

    public function update(Request $request, AutomacaoSequencia $sequencia)
    {
        abort_unless($sequencia->tenant_id === $this->tenantId(), 403);

        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'descricao'     => 'nullable|string|max:1000',
            'gatilho'       => 'required|in:manual,pipeline_status,novo_lead',
            'gatilho_valor' => 'nullable|string|max:100',
            'ativo'         => 'boolean',

            'etapas'                     => 'required|array|min:1',
            'etapas.*.canal'             => 'required|in:whatsapp,email,ambos',
            'etapas.*.delay_dias'        => 'required|integer|min:0|max:365',
            'etapas.*.delay_horas'       => 'required|integer|min:0|max:23',
            'etapas.*.tipo_mensagem'     => 'required|in:ia,template',
            'etapas.*.instrucao_ia'      => 'nullable|string|max:1000',
            'etapas.*.template_mensagem' => 'nullable|string|max:2000',
            'etapas.*.assunto_email'     => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $sequencia, $request) {
            $sequencia->update([
                'nome'          => $validated['nome'],
                'descricao'     => $validated['descricao'],
                'gatilho'       => $validated['gatilho'],
                'gatilho_valor' => $validated['gatilho_valor'],
                'ativo'         => $request->boolean('ativo'),
            ]);

            // Apaga etapas antigas e recria
            $sequencia->etapas()->delete();

            foreach ($validated['etapas'] as $ordem => $etapaDados) {
                $sequencia->etapas()->create([
                    'ordem'              => $ordem + 1,
                    'canal'              => $etapaDados['canal'],
                    'delay_dias'         => (int) $etapaDados['delay_dias'],
                    'delay_horas'        => (int) $etapaDados['delay_horas'],
                    'tipo_mensagem'      => $etapaDados['tipo_mensagem'],
                    'instrucao_ia'       => $etapaDados['instrucao_ia'] ?? null,
                    'template_mensagem'  => $etapaDados['template_mensagem'] ?? null,
                    'assunto_email'      => $etapaDados['assunto_email'] ?? null,
                ]);
            }
        });

        return redirect()->route('crm.sequencias.index')
            ->with('success', 'Sequência atualizada!');
    }

    // ─── Excluir ─────────────────────────────────────────────────────────────

    public function destroy(AutomacaoSequencia $sequencia)
    {
        abort_unless($sequencia->tenant_id === $this->tenantId(), 403);
        $sequencia->delete();

        return redirect()->route('crm.sequencias.index')
            ->with('success', 'Sequência excluída.');
    }

    // ─── Ativar / Pausar ─────────────────────────────────────────────────────

    public function toggleAtivo(AutomacaoSequencia $sequencia)
    {
        abort_unless($sequencia->tenant_id === $this->tenantId(), 403);
        $sequencia->update(['ativo' => ! $sequencia->ativo]);

        $status = $sequencia->ativo ? 'ativada' : 'pausada';
        return back()->with('success', "Sequência {$status}!");
    }

    // ─── Disparo manual para um lead ─────────────────────────────────────────

    public function dispararParaLead(Request $request, AutomacaoSequencia $sequencia, AutomacaoService $service)
    {
        abort_unless($sequencia->tenant_id === $this->tenantId(), 403);

        $request->validate(['lead_id' => 'required|integer|exists:leads,id']);

        $lead = Lead::find($request->lead_id);

        abort_unless($lead->tenant_id === $this->tenantId(), 403);

        $sequencia->load('etapas');
        $service->iniciarSequencia($lead, $sequencia);

        return back()->with('success', "Sequência disparada para {$lead->nome}!");
    }

    // ─── Envios recentes (histórico) ─────────────────────────────────────────

    public function envios(AutomacaoSequencia $sequencia)
    {
        abort_unless($sequencia->tenant_id === $this->tenantId(), 403);

        $envios = AutomacaoEnvio::where('sequencia_id', $sequencia->id)
            ->with(['lead', 'etapa'])
            ->latest()
            ->paginate(50);

        return view('crm.sequencias.envios', compact('sequencia', 'envios'));
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);
        return (int) Auth::user()->empresa->id;
    }
}
