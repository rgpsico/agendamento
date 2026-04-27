<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarEmailLeadJob;
use App\Models\Lead;
use App\Models\Usuario;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('responsavel');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('origem')) {
            $query->where('origem', $request->origem);
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

        $leads = $query->latest()->paginate(20)->withQueryString();

        return view('admin.leads.index', [
            'leads'      => $leads,
            'statusList' => Lead::$statusList,
            'origens'    => Lead::$origens,
        ]);
    }

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
            'origem'         => 'required|string',
            'status'         => 'required|string',
            'interesse'      => 'nullable|string|max:255',
            'observacoes'    => 'nullable|string',
            'responsavel_id' => 'nullable|exists:usuarios,id',
        ]);

        Lead::create($validated);

        return redirect()->route('admin.leads.index')->with('success', 'Lead cadastrado com sucesso!');
    }

    public function show(Lead $lead)
    {
        $lead->load('responsavel');

        return view('admin.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        return view('admin.leads.edit', [
            'lead'         => $lead,
            'statusList'   => Lead::$statusList,
            'origens'      => Lead::$origens,
            'responsaveis' => Usuario::orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'nome'           => 'required|string|max:255',
            'email'          => 'nullable|email|max:255',
            'telefone'       => 'nullable|string|max:20',
            'empresa'        => 'nullable|string|max:255',
            'origem'         => 'required|string',
            'status'         => 'required|string',
            'interesse'      => 'nullable|string|max:255',
            'observacoes'    => 'nullable|string',
            'responsavel_id' => 'nullable|exists:usuarios,id',
        ]);

        $lead->update($validated);

        return redirect()->route('admin.leads.index')->with('success', 'Lead atualizado com sucesso!');
    }

    public function enviarEmails(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:leads,id',
        ]);

        $leads = Lead::whereIn('id', $request->ids)
                     ->whereNotNull('email')
                     ->get();

        $despachados = 0;
        $semEmail    = count($request->ids) - $leads->count();

        foreach ($leads as $lead) {
            EnviarEmailLeadJob::dispatch($lead)->onQueue('default');
            $despachados++;
        }

        $msg = "{$despachados} e-mail(s) adicionado(s) à fila para envio.";
        if ($semEmail > 0) {
            $msg .= " {$semEmail} lead(s) ignorado(s) por não ter e-mail cadastrado.";
        }

        return redirect()->back()->with('success', $msg);
    }

    public function whatsapp(Lead $lead)
    {
        abort_unless($lead->whatsapp_url, 404);

        $lead->update(['whatsapp_enviado_em' => now()]);

        return redirect()->away($lead->whatsapp_url);
    }

    public function resetar(Lead $lead)
    {
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

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead excluído com sucesso!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file    = $request->file('arquivo');
        $handle  = fopen($file->getRealPath(), 'r');
        $header  = null;
        $imported = 0;
        $errors  = [];

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            // Pula linha de cabeçalho
            if ($header === null) {
                $header = array_map('strtolower', array_map('trim', $row));
                continue;
            }

            if (count($row) < 2) continue;

            $data = array_combine($header, array_map('trim', $row));

            $nome = $data['nome'] ?? $data['nome do negocio'] ?? $data['negocio'] ?? null;

            if (empty($nome)) {
                $errors[] = "Linha ignorada: nome vazio.";
                continue;
            }

            Lead::create([
                'nome'      => $nome,
                'telefone'  => $data['telefone'] ?? $data['whatsapp'] ?? $data['whatsapp / telefone'] ?? null,
                'email'     => $data['email'] ?? $data['e-mail'] ?? null,
                'interesse' => $data['interesse'] ?? $data['tipo'] ?? null,
                'origem'    => $data['origem'] ?? 'manual',
                'status'    => 'novo',
            ]);

            $imported++;
        }

        fclose($handle);

        $msg = "{$imported} lead(s) importado(s) com sucesso.";
        if (count($errors)) {
            $msg .= ' ' . count($errors) . ' linha(s) ignorada(s).';
        }

        return redirect()->route('admin.leads.index')->with('success', $msg);
    }

    public function importText(Request $request)
    {
        $request->validate([
            'conteudo' => 'required|string',
        ]);

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

            $nome = $data['nome']
                ?? $data['nome_negocio']
                ?? $data['nome do negocio']
                ?? $data['negocio']
                ?? null;

            if (empty($nome)) {
                $errors[] = "Linha ignorada: nome vazio.";
                continue;
            }

            Lead::create([
                'nome'      => $nome,
                'telefone'  => $data['telefone'] ?? $data['whatsapp'] ?? null,
                'email'     => $data['email'] ?? $data['e-mail'] ?? null,
                'interesse' => $data['interesse'] ?? $data['tipo'] ?? null,
                'origem'    => $data['origem'] ?? 'manual',
                'status'    => 'novo',
            ]);

            $imported++;
        }

        $msg = "{$imported} lead(s) importado(s) com sucesso.";
        if (count($errors)) {
            $msg .= ' ' . count($errors) . ' linha(s) ignorada(s).';
        }

        return redirect()->route('admin.leads.index')->with('success', $msg);
    }

    public function templateCsv()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_leads.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($handle, ['nome', 'telefone', 'email', 'interesse', 'origem']);
            fputcsv($handle, ['Peninsula Pilates Studio', '(21) 99835-6116', 'pilatespeninsula@gmail.com', 'Pilates', 'manual']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
