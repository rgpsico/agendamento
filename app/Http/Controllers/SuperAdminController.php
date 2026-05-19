<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Lead;
use App\Models\Modalidade;
use Illuminate\Http\Request;

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

        return view('super_admin.crm.leads', compact('leads', 'totais'));
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

    public function crmMover(Request $request, Lead $lead)
    {
        $request->validate(['pipeline_status' => 'required|in:' . implode(',', array_keys(Lead::$pipelineStatus))]);
        abort_unless(is_null($lead->tenant_id), 403);

        $lead->update(['pipeline_status' => $request->pipeline_status]);

        return back()->with('success', 'Lead movido.');
    }

    public function toggleStatus(Empresa $empresa)
    {
        $empresa->status = $empresa->status === 'ativo' ? 'inativo' : 'ativo';
        $empresa->save();

        return back()->with('success', "Status da empresa '{$empresa->nome}' atualizado.");
    }
}
