<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\WidgetEvento;
use App\Models\WidgetSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MetricaController extends Controller
{
    // ─── Sites (CRUD) ────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $tenantId = $this->tenantId();
        $sites    = WidgetSite::forTenant($tenantId)->latest()->get();

        // Site selecionado para ver métricas
        $siteId   = $request->input('site_id', $sites->first()?->id);
        $site     = $sites->find($siteId);

        // Período
        $dias  = (int) $request->input('dias', 30);
        $desde = now()->subDays($dias)->startOfDay();

        $dados = $site ? $this->calcularMetricas($site, $desde) : null;

        return view('crm.metricas.index', compact('sites', 'site', 'dias', 'dados'));
    }

    public function storeSite(Request $request)
    {
        $validated = $request->validate([
            'nome'    => 'required|string|max:255',
            'dominio' => 'nullable|string|max:255',
        ]);

        $validated['tenant_id'] = $this->tenantId();

        WidgetSite::create($validated);

        return redirect()->route('crm.metricas.index')->with('success', 'Site criado com sucesso!');
    }

    public function destroySite(WidgetSite $widgetSite)
    {
        abort_unless($widgetSite->tenant_id === $this->tenantId(), 403);
        $widgetSite->delete();

        return redirect()->route('crm.metricas.index')->with('success', 'Site excluído.');
    }

    // ─── Cálculo de métricas ─────────────────────────────────────────────────

    private function calcularMetricas(WidgetSite $site, $desde): array
    {
        $base = WidgetEvento::where('site_id', $site->id)
                            ->where('created_at', '>=', $desde);

        // KPIs
        $visitantes    = (clone $base)->where('tipo', 'visita')->distinct('session_id')->count('session_id');
        $totalVisitas  = (clone $base)->where('tipo', 'visita')->count();
        $whatsappClicks= (clone $base)->where('tipo', 'whatsapp')->count();
        $botAberturas  = (clone $base)->where('tipo', 'bot_open')->count();
        $modalViews    = (clone $base)->where('tipo', 'modal_view')->count();
        $modalConverts = (clone $base)->where('tipo', 'modal_convert')->count();

        // Tempo médio (segundos → minutos)
        $tempoMedio = (clone $base)->where('tipo', 'tempo')
                                   ->whereNotNull('duracao')
                                   ->avg('duracao') ?? 0;

        // Conversão modal
        $taxaConversao = $modalViews > 0 ? round($modalConverts / $modalViews * 100, 1) : 0;

        // Gráfico: visitantes únicos por dia (últimos N dias)
        $graficoDias = (clone $base)
            ->where('tipo', 'visita')
            ->selectRaw('DATE(created_at) as dia, COUNT(DISTINCT session_id) as total')
            ->groupBy('dia')
            ->orderBy('dia')
            ->pluck('total', 'dia');

        // Preenche dias sem dados com 0
        $graficoLabels = [];
        $graficoData   = [];
        $cursor = now()->subDays(29)->startOfDay();
        while ($cursor->lte(now())) {
            $key = $cursor->toDateString();
            $graficoLabels[] = $cursor->format('d/m');
            $graficoData[]   = $graficoDias[$key] ?? 0;
            $cursor->addDay();
        }

        // Top páginas
        $topPaginas = (clone $base)
            ->where('tipo', 'visita')
            ->whereNotNull('pagina')
            ->selectRaw('pagina, COUNT(DISTINCT session_id) as visitantes, COUNT(*) as visualizacoes')
            ->groupBy('pagina')
            ->orderByDesc('visitantes')
            ->limit(10)
            ->get();

        // Dispositivos
        $dispositivos = (clone $base)
            ->where('tipo', 'visita')
            ->selectRaw('dispositivo, COUNT(DISTINCT session_id) as total')
            ->groupBy('dispositivo')
            ->pluck('total', 'dispositivo')
            ->toArray();

        // Top referrers
        $topReferrers = (clone $base)
            ->where('tipo', 'visita')
            ->whereNotNull('referrer')
            ->where('referrer', '<>', '')
            ->selectRaw('referrer, COUNT(DISTINCT session_id) as total')
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return compact(
            'visitantes', 'totalVisitas', 'whatsappClicks',
            'botAberturas', 'modalViews', 'modalConverts',
            'tempoMedio', 'taxaConversao',
            'graficoLabels', 'graficoData',
            'topPaginas', 'dispositivos', 'topReferrers'
        );
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);
        return (int) Auth::user()->empresa->id;
    }
}
