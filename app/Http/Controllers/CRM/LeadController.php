<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Lead;
use App\Models\Tarefa;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Lead::class);

        $tenantId = $this->tenantId();
        $query = Lead::forTenant($tenantId)->with(['campanha', 'responsavel']);

        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%')
                    ->orWhere('email', 'like', '%' . $request->busca . '%')
                    ->orWhere('telefone', 'like', '%' . $request->busca . '%');
            });
        }

        if ($request->filled('pipeline_status')) {
            $query->where('pipeline_status', $request->pipeline_status);
        }

        if ($request->filled('origem')) {
            $query->where('origem', $request->origem);
        }

        $leads = $query->latest()->paginate(20)->withQueryString();

        return view('crm.leads.index', [
            'leads' => $leads,
            'statuses' => Lead::$pipelineStatus,
            'origens' => Lead::$origens,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Lead::class);

        return view('crm.leads.create', $this->formData());
    }

    public function store(Request $request)
    {
        $this->authorize('create', Lead::class);

        $tenantId = $this->tenantId();
        $validated = $this->validateLead($request, $tenantId);
        $validated['tenant_id'] = $tenantId;
        $validated['status'] = $this->statusComercial($validated['pipeline_status'] ?? 'novo_lead');

        $lead = Lead::create($validated);

        return redirect()->route('crm.leads.show', $lead)->with('success', 'Lead cadastrado com sucesso.');
    }

    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);

        $lead->load(['campanha', 'responsavel', 'historicos.usuario', 'tarefas.usuario']);

        return view('crm.leads.show', compact('lead'));
    }

    public function whatsapp(Lead $lead)
    {
        $this->authorize('update', $lead);

        $lead->update(['whatsapp_enviado_em' => now()]);

        abort_unless($lead->whatsapp_url, 404);

        return redirect()->away($lead->whatsapp_url);
    }

    public function edit(Lead $lead)
    {
        $this->authorize('update', $lead);

        return view('crm.leads.edit', $this->formData() + compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $validated = $this->validateLead($request, $this->tenantId());
        $validated['status'] = $this->statusComercial($validated['pipeline_status'] ?? $lead->pipeline_status);
        $lead->update($validated);

        return redirect()->route('crm.leads.show', $lead)->with('success', 'Lead atualizado com sucesso.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);

        $lead->delete();

        return redirect()->route('crm.leads.index')->with('success', 'Lead excluido com sucesso.');
    }

    private function formData(): array
    {
        $tenantId = $this->tenantId();

        return [
            'statuses' => Lead::$pipelineStatus,
            'origens' => Lead::$origens,
            'campanhas' => Campanha::forTenant($tenantId)->orderBy('nome')->get(),
            'responsaveis' => Usuario::orderBy('nome')->get(),
        ];
    }

    private function validateLead(Request $request, int $tenantId): array
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:30',
            'origem' => 'required|string|max:50',
            'interesse' => 'nullable|string|max:255',
            'pipeline_status' => 'required|string|in:' . implode(',', array_keys(Lead::$pipelineStatus)),
            'valor_estimado' => 'nullable|numeric|min:0|max:9999999',
            'responsavel_id' => 'nullable|exists:usuarios,id',
            'campanha_id' => 'nullable|exists:campanhas,id',
            'observacoes' => 'nullable|string',
        ]);

        if (! empty($validated['campanha_id'])) {
            Campanha::forTenant($tenantId)->findOrFail($validated['campanha_id']);
        }

        return $validated;
    }

    private function statusComercial(string $pipelineStatus): string
    {
        return match ($pipelineStatus) {
            'novo_lead' => 'novo',
            'em_contato' => 'em_contato',
            'aula_experimental' => 'qualificado',
            'matriculado', 'recorrente' => 'convertido',
            default => 'novo',
        };
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
