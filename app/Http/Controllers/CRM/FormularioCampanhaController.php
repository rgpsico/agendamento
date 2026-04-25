<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormularioCampanhaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Campanha::class);

        $campanhas = Campanha::forTenant($this->tenantId())
            ->withCount('leads')
            ->latest()
            ->paginate(20);

        return view('crm.formularios.index', compact('campanhas'));
    }

    public function update(Request $request, Campanha $campanha)
    {
        $this->authorize('update', $campanha);

        $validated = $request->validate([
            'formulario_titulo' => 'nullable|string|max:255',
            'formulario_descricao' => 'nullable|string|max:2000',
            'formulario_botao' => 'required|string|max:80',
            'formulario_ativo' => 'nullable|boolean',
        ]);

        $validated['formulario_ativo'] = $request->boolean('formulario_ativo');
        $campanha->update($validated);

        return back()->with('success', 'Formulario atualizado com sucesso.');
    }

    public function show(string $token)
    {
        $campanha = Campanha::where('public_token', $token)
            ->where('ativo', true)
            ->where('formulario_ativo', true)
            ->firstOrFail();

        return view('crm.formularios.public', compact('campanha'));
    }

    public function store(Request $request, string $token)
    {
        $campanha = Campanha::where('public_token', $token)
            ->where('ativo', true)
            ->where('formulario_ativo', true)
            ->firstOrFail();

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'interesse' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string|max:2000',
        ]);

        $lead = Lead::create([
            'tenant_id' => $campanha->tenant_id,
            'campanha_id' => $campanha->id,
            'nome' => $validated['nome'],
            'telefone' => $validated['telefone'],
            'email' => $validated['email'] ?? null,
            'interesse' => $validated['interesse'] ?? null,
            'observacoes' => $validated['observacoes'] ?? null,
            'origem' => $campanha->canal,
            'status' => 'novo',
            'pipeline_status' => 'novo_lead',
        ]);

        return view('crm.formularios.obrigado', compact('campanha', 'lead'));
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
