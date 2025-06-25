<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalyticsEvent;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AnalyticsController extends Controller
{
    public function recordEvent(Request $request)
    {
        $data = $request->validate([
            'empresa_id' => 'nullable|integer',
            'event_type' => 'required|string',
            'value' => 'nullable|numeric',
        ]);

        AnalyticsEvent::create($data);

        return response()->json(['status' => 'ok']);
    }

    public function dashboard(Request $request)
    {
        if (!auth()->check() || !auth()->user()->isAdmin) {
            abort(403);
        }

        $range = $request->get('range', '7days');

        $start = match ($range) {
            'today' => Carbon::today(),
            'month' => Carbon::now()->startOfMonth(),
            default => Carbon::now()->subDays(6),
        };

        $empresaId = auth()->user()->empresa->id ?? null;

        $events = AnalyticsEvent::where('created_at', '>=', $start)
            ->when($empresaId, fn($q) => $q->where('empresa_id', $empresaId))
            ->get()
            ->groupBy(fn($e) => $e->created_at->format('Y-m-d'));

        $labels = [];
        $visits = [];
        $whatsapp = [];
        $load = [];

        foreach (CarbonPeriod::create($start, Carbon::today()) as $date) {
            $day = $date->format('Y-m-d');
            $labels[] = $day;
            $dayEvents = $events[$day] ?? collect();
            $visits[] = $dayEvents->where('event_type', 'visit')->count();
            $whatsapp[] = $dayEvents->where('event_type', 'whatsapp_click')->count();
            $load[] = round($dayEvents->where('event_type', 'page_load_time')->avg('value'), 2);
        }

        $chartData = [
            'labels' => $labels,
            'visits' => $visits,
            'whatsapp' => $whatsapp,
            'load' => $load,
        ];

        return view('analytics.dashboard', compact('chartData', 'range'));
    }
}
