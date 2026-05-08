<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarEmailLeadJob;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    // ─── Listagem ────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $tenantId = $this->tenantId();
        $query    = Lead::forTenant($tenantId)->with('responsavel');
        $perPage  = $this->perPage($request);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('origem')) {
            $query->where('origem', $request->origem);
        }

        if ($request->filled('bairro')) {
            $query->where('bairro', $request->bairro);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        if ($request->temperatura === 'quente') {
            $query->whereNotNull('interessado_em');
        } elseif ($request->temperatura === 'morno') {
            $query->whereNotNull('morno_em')->whereNull('interessado_em');
        } elseif ($request->temperatura === 'frio') {
            $query->whereNull('morno_em');
        }

        if ($request->email_status === 'nao_enviado') {
            $query->whereNull('email_enviado_em');
        } elseif ($request->email_status === 'enviado') {
            $query->whereNotNull('email_enviado_em');
        }

        if ($request->whatsapp_status === 'nao_enviado') {
            $query->whereNull('whatsapp_enviado_em');
        } elseif ($request->whatsapp_status === 'enviado') {
            $query->whereNotNull('whatsapp_enviado_em');
        }

        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%')
                  ->orWhere('email', 'like', '%' . $request->busca . '%')
                  ->orWhere('telefone', 'like', '%' . $request->busca . '%');
            });
        }

        if ($request->ordem === 'antigos') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $leads = $query->paginate($perPage)->withQueryString();

        $emailTemplates = EmailTemplate::where('tenant_id', $tenantId)
            ->where('ativo', true)->orderBy('nome')->get();

        return view('admin.leads.index', [
            'leads'          => $leads,
            'statusList'     => Lead::$statusList,
            'origens'        => Lead::$origens,
            'bairros'        => Lead::forTenant($tenantId)
                ->whereNotNull('bairro')->where('bairro', '<>', '')
                ->distinct()->orderBy('bairro')->pluck('bairro'),
            'emailTemplates' => $emailTemplates,
        ]);
    }

    // ─── Criar ───────────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.leads.create', [
            'statusList'   => Lead::$statusList,
            'origens'      => Lead::$origens,
            'responsaveis' => Usuario::orderBy('nome')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'telefone'       => 'nullable|string|max:20',
            'empresa'        => 'nullable|string|max:255',
            'bairro'         => 'nullable|string|max:255',
            'origem'         => 'required|string',
            'status'         => 'required|string',
            'interesse'      => 'nullable|string|max:255',
            'observacoes'    => 'nullable|string',
            'responsavel_id' => 'nullable|exists:usuarios,id',
        ]);

        $validated['tenant_id'] = $this->tenantId();

        Lead::create($validated);

        return redirect()->route('admin.leads.index')->with('success', 'Lead cadastrado com sucesso!');
    }

    // ─── Ver / Editar / Atualizar / Excluir ──────────────────────────────────

    public function show(Lead $lead)
    {
        $this->autorizarLead($lead);
        $lead->load('responsavel');

        return view('admin.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->autorizarLead($lead);

        return view('admin.leads.edit', [
            'lead'         => $lead,
            'statusList'   => Lead::$statusList,
            'origens'      => Lead::$origens,
            'responsaveis' => Usuario::orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $this->autorizarLead($lead);

        $validated = $request->validate([
            'nome'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'telefone'       => 'nullable|string|max:20',
            'empresa'        => 'nullable|string|max:255',
            'bairro'         => 'nullable|string|max:255',
            'origem'         => 'required|string',
            'status'         => 'required|string',
            'interesse'      => 'nullable|string|max:255',
            'observacoes'    => 'nullable|string',
            'responsavel_id' => 'nullable|exists:usuarios,id',
        ]);

        $lead->update($validated);

        return redirect()->route('admin.leads.index')->with('success', 'Lead atualizado com sucesso!');
    }

    public function destroy(Lead $lead)
    {
        $this->autorizarLead($lead);
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead excluído com sucesso!');
    }

    // ─── Ações ───────────────────────────────────────────────────────────────

    public function whatsapp(Lead $lead)
    {
        $this->autorizarLead($lead);
        abort_unless($lead->whatsapp_url, 404);

        $lead->update(['whatsapp_enviado_em' => now()]);

        return redirect()->away($lead->whatsapp_url);
    }

    public function resetar(Lead $lead)
    {
        $this->autorizarLead($lead);

        $lead->update([
            'status'              => 'novo',
            'email_enviado_em'    => null,
            'whatsapp_enviado_em' => null,
            'morno_em'            => null,
            'interessado_em'      => null,
            'whatsapp_confirmado' => null,
            'trial_usuario_id'    => null,
        ]);

        return redirect()->route('admin.leads.show', $lead)
                         ->with('success', 'Lead resetado ao início do funil.');
    }

    public function enviarEmails(Request $request)
    {
        $tenantId = $this->tenantId();

        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $leads = Lead::forTenant($tenantId)
                     ->whereIn('id', $request->ids)
                     ->whereNotNull('email')
                     ->get();

        $despachados = 0;

        foreach ($leads as $lead) {
            EnviarEmailLeadJob::dispatch($lead)->onQueue('default');
            $despachados++;
        }

        $ignorados = count($request->ids) - $despachados;
        $msg = "{$despachados} e-mail(s) adicionado(s) à fila.";
        if ($ignorados > 0) {
            $msg .= " {$ignorados} lead(s) ignorado(s) (sem e-mail ou de outra empresa).";
        }

        return redirect()->back()->with('success', $msg);
    }

    // ─── Importação CSV ──────────────────────────────────────────────────────

    public function import(Request $request)
    {
        $request->validate(['arquivo' => 'required|file|mimes:csv,txt|max:2048']);

        $tenantId = $this->tenantId();
        $handle   = fopen($request->file('arquivo')->getRealPath(), 'r');
        $header   = null;
        $imported = 0;
        $errors   = [];

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($header === null) {
                $header = array_map('strtolower', array_map('trim', $row));
                continue;
            }

            if (count($row) < 2) continue;

            $data = array_combine($header, array_map('trim', $row));
            $nome = $data['nome'] ?? $data['nome do negocio'] ?? $data['negocio'] ?? null;

            if (empty($nome)) { $errors[] = "Linha ignorada: nome vazio."; continue; }

            Lead::create([
                'tenant_id' => $tenantId,
                'nome'      => $nome,
                'telefone'  => $data['telefone'] ?? $data['whatsapp'] ?? null,
                'email'     => $data['email'] ?? $data['e-mail'] ?? null,
                'bairro'    => $data['bairro'] ?? null,
                'interesse' => $data['interesse'] ?? $data['tipo'] ?? null,
                'origem'    => $data['origem'] ?? 'manual',
                'status'    => 'novo',
                'pipeline_status' => 'novo_lead',
            ]);

            $imported++;
        }

        fclose($handle);

        return redirect()->route('admin.leads.index')
            ->with('success', "{$imported} lead(s) importado(s). " . count($errors) . " ignorado(s).");
    }

    public function importText(Request $request)
    {
        $request->validate(['conteudo' => 'required|string']);

        $tenantId = $this->tenantId();
        $lines    = explode("\n", trim($request->conteudo));
        $header   = null;
        $imported = 0;
        $errors   = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $row = str_getcsv($line);

            if ($header === null) {
                $header = array_map(fn($h) => strtolower(trim($h)), $row);
                continue;
            }

            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), null);
            }

            $data = array_combine($header, array_map('trim', $row));
            $nome = $data['nome'] ?? $data['nome_negocio'] ?? $data['nome do negocio'] ?? null;

            if (empty($nome)) { $errors[] = "Linha ignorada: nome vazio."; continue; }

            Lead::create([
                'tenant_id' => $tenantId,
                'nome'      => $nome,
                'telefone'  => $data['telefone'] ?? $data['whatsapp'] ?? null,
                'email'     => $data['email'] ?? $data['e-mail'] ?? null,
                'bairro'    => $data['bairro'] ?? null,
                'interesse' => $data['interesse'] ?? $data['tipo'] ?? null,
                'origem'    => $data['origem'] ?? 'manual',
                'status'    => 'novo',
                'pipeline_status' => 'novo_lead',
            ]);

            $imported++;
        }

        return redirect()->route('admin.leads.index')
            ->with('success', "{$imported} lead(s) importado(s). " . count($errors) . " ignorado(s).");
    }

    public function templateCsv()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_leads.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['nome', 'telefone', 'email', 'bairro', 'interesse', 'origem']);
            fputcsv($handle, ['Peninsula Pilates Studio', '(21) 99835-6116', 'pilatespeninsula@gmail.com', 'Barra da Tijuca', 'Pilates', 'manual']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403, 'Sua conta não está associada a nenhuma empresa.');
        return (int) Auth::user()->empresa->id;
    }

    private function autorizarLead(Lead $lead): void
    {
        abort_unless($lead->tenant_id === $this->tenantId(), 403, 'Acesso negado.');
    }

    private function perPage(Request $request): int
    {
        if ($request->per_page === 'all') {
            return max(Lead::forTenant($this->tenantId())->count(), 1);
        }

        return in_array((int) $request->per_page, [20, 100, 200], true)
            ? (int) $request->per_page
            : 20;
    }
}
