<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampanhaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Campanha::class);

        $campanhas = Campanha::forTenant($this->tenantId())
            ->withCount([
                'leads',
                'leads as convertidos_count' => fn ($q) => $q->whereIn('pipeline_status', ['matriculado', 'recorrente']),
            ])
            ->latest()
            ->paginate(20);

        return view('crm.campanhas.index', compact('campanhas'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Campanha::class);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'canal' => 'required|string|max:80',
            'inicio' => 'nullable|date',
            'fim' => 'nullable|date|after_or_equal:inicio',
            'custo' => 'nullable|numeric|min:0|max:9999999',
            'ativo' => 'nullable|boolean',
        ]);

        $validated['tenant_id'] = $this->tenantId();
        $validated['ativo'] = $request->boolean('ativo', true);

        Campanha::create($validated);

        return back()->with('success', 'Campanha cadastrada com sucesso.');
    }

    public function update(Request $request, Campanha $campanha)
    {
        $this->authorize('update', $campanha);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'canal' => 'required|string|max:80',
            'inicio' => 'nullable|date',
            'fim' => 'nullable|date|after_or_equal:inicio',
            'custo' => 'nullable|numeric|min:0|max:9999999',
            'ativo' => 'nullable|boolean',
        ]);
        $validated['ativo'] = $request->boolean('ativo');

        $campanha->update($validated);

        return back()->with('success', 'Campanha atualizada com sucesso.');
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
