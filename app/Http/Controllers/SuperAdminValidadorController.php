<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadQualificacao;
use Illuminate\Http\Request;

/** Painel do super admin: gera links do validador e mostra as respostas dos leads. */
class SuperAdminValidadorController extends Controller
{
    public function index(Request $request)
    {
        $query = LeadQualificacao::with('lead')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('nicho')) {
            $query->where('nicho', $request->nicho);
        }
        if ($request->filled('nivel')) {
            $query->where('nivel_digital', $request->nivel);
        }
        if ($request->filled('busca')) {
            $b = '%' . $request->busca . '%';
            $query->where(fn ($q) => $q->where('nome', 'like', $b)->orWhere('cidade', 'like', $b)->orWhere('whatsapp', 'like', $b));
        }

        $itens = $query->paginate(25)->withQueryString();

        return view('super_admin.validador.index', [
            'itens'     => $itens,
            'totais'    => [
                'total'      => LeadQualificacao::count(),
                'respondido' => LeadQualificacao::where('status', 'respondido')->count(),
                'pendente'   => LeadQualificacao::where('status', 'pendente')->count(),
            ],
        ]);
    }

    /** Cria um link novo (avulso ou ligado a um lead do CRM). */
    public function store(Request $request)
    {
        $data = $request->validate(['lead_id' => 'nullable|integer']);

        $leadId = null;
        if (! empty($data['lead_id'])) {
            $lead = Lead::whereNull('tenant_id')->findOrFail($data['lead_id']);
            $leadId = $lead->id;

            // Reaproveita o link pendente do mesmo lead em vez de gerar vários
            $existente = LeadQualificacao::where('lead_id', $leadId)->where('status', 'pendente')->latest()->first();
            if ($existente) {
                return redirect()->route('super.admin.validador.show', $existente);
            }
        }

        $q = LeadQualificacao::create(['lead_id' => $leadId]);

        return redirect()->route('super.admin.validador.show', $q)->with('success', 'Link criado. É só enviar pro lead.');
    }

    public function show(LeadQualificacao $qualificacao)
    {
        $q = $qualificacao->load('lead');
        $link = route('validador.public', $q->token);

        $telefone = preg_replace('/\D/', '', $q->whatsapp ?: ($q->lead->telefone ?? ''));
        if ($telefone && strlen($telefone) <= 11) {
            $telefone = '55' . $telefone;
        }
        $nomeLead = $q->nome ?: ($q->lead->nome ?? '');
        $mensagem = trim(($nomeLead ? "Olá, {$nomeLead}! " : 'Olá! ')
            . "Pra eu te atender melhor, responde esse formulário rápido (1 minuto): {$link}");

        return view('super_admin.validador.show', [
            'q'        => $q,
            'link'     => $link,
            'whatsapp' => $telefone ? 'https://wa.me/' . $telefone . '?text=' . rawurlencode($mensagem) : null,
            'mensagem' => $mensagem,
        ]);
    }

    public function destroy(LeadQualificacao $qualificacao)
    {
        $qualificacao->delete();

        return redirect()->route('super.admin.validador.index')->with('success', 'Registro removido.');
    }
}
