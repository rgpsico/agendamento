<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Lead;
use App\Models\PlanoAluno;
use App\Models\Professor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function index()
    {
        $this->authorize('viewReports', Lead::class);

        $tenantId = $this->tenantId();
        $leads = Lead::forTenant($tenantId);
        $professorIds = Professor::where('empresa_id', $tenantId)->pluck('id');

        $conversaoPorOrigem = (clone $leads)
            ->select('origem', DB::raw('count(*) as total'), DB::raw("sum(case when pipeline_status in ('matriculado', 'recorrente') then 1 else 0 end) as convertidos"))
            ->groupBy('origem')
            ->get()
            ->map(fn ($row) => [
                'origem' => $row->origem,
                'total' => (int) $row->total,
                'convertidos' => (int) $row->convertidos,
                'taxa' => $row->total > 0 ? round(($row->convertidos / $row->total) * 100, 1) : 0,
            ]);

        $driver = DB::connection()->getDriverName();
        $monthExpression = $driver === 'pgsql'
            ? "to_char(created_at, 'YYYY-MM')"
            : "date_format(created_at, '%Y-%m')";

        $evolucaoMatriculas = (clone $leads)
            ->whereIn('pipeline_status', ['matriculado', 'recorrente'])
            ->selectRaw("{$monthExpression} as mes, count(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $aulasCanceladasMes = Agendamento::whereIn('professor_id', $professorIds)
            ->whereMonth('data_da_aula', now()->month)
            ->whereYear('data_da_aula', now()->year)
            ->where('status', 'cancelada')
            ->count();
        $aulasMes = Agendamento::whereIn('professor_id', $professorIds)
            ->whereMonth('data_da_aula', now()->month)
            ->whereYear('data_da_aula', now()->year)
            ->count();
        $churnMensal = $aulasMes > 0 ? round(($aulasCanceladasMes / $aulasMes) * 100, 1) : 0;

        $ltvMedio = class_exists(PlanoAluno::class) ? (float) PlanoAluno::avg('valor') : 0;

        return view('crm.relatorios.index', compact('conversaoPorOrigem', 'evolucaoMatriculas', 'churnMensal', 'ltvMedio'));
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
