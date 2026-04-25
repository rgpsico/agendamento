<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Campanha;
use App\Models\Lead;
use App\Models\Professor;
use App\Models\Tarefa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardCRMController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', Lead::class);

        $tenantId = $this->tenantId();
        $leadQuery = Lead::forTenant($tenantId);
        $leadIds = (clone $leadQuery)->pluck('id');
        $professorIds = Professor::where('empresa_id', $tenantId)->pluck('id');

        $novosLeads30d = (clone $leadQuery)->where('created_at', '>=', now()->subDays(30))->count();
        $convertidos30d = (clone $leadQuery)->whereIn('pipeline_status', ['matriculado', 'recorrente'])->where('created_at', '>=', now()->subDays(30))->count();
        $taxaConversao = $novosLeads30d > 0 ? round(($convertidos30d / $novosLeads30d) * 100, 1) : 0;
        $aulasSemana = Agendamento::whereIn('professor_id', $professorIds)
            ->whereBetween('data_da_aula', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $custoCampanhas = Campanha::forTenant($tenantId)->sum('custo');
        $totalLeadsCampanha = (clone $leadQuery)->whereNotNull('campanha_id')->count();
        $cpl = $totalLeadsCampanha > 0 ? round($custoCampanhas / $totalLeadsCampanha, 2) : 0;

        $funil = collect(Lead::$pipelineStatus)->map(function ($label, $status) use ($leadQuery) {
            $total = (clone $leadQuery)->count();
            $count = (clone $leadQuery)->where('pipeline_status', $status)->count();

            return [
                'status' => $status,
                'label' => $label,
                'count' => $count,
                'percent' => $total > 0 ? round(($count / $total) * 100, 1) : 0,
            ];
        })->values();

        $origens = (clone $leadQuery)
            ->select('origem', DB::raw('count(*) as total'))
            ->groupBy('origem')
            ->pluck('total', 'origem');

        $campanhas = Campanha::forTenant($tenantId)
            ->withCount('leads')
            ->where('ativo', true)
            ->latest()
            ->limit(8)
            ->get();

        $tarefasHoje = Tarefa::forTenant($tenantId)
            ->with('lead')
            ->where('concluida', false)
            ->whereDate('vencimento', '<=', Carbon::today())
            ->orderBy('vencimento')
            ->get();

        return view('crm.dashboard', compact('novosLeads30d', 'taxaConversao', 'aulasSemana', 'cpl', 'funil', 'origens', 'campanhas', 'tarefasHoje'));
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
