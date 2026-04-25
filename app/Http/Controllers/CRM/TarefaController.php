<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', Tarefa::class);

        $tenantId = $this->tenantId();
        $validated = $request->validate([
            'lead_id' => 'nullable|exists:leads,id',
            'tipo' => 'required|string|max:80',
            'descricao' => 'required|string|max:2000',
            'vencimento' => 'nullable|date',
        ]);

        if (! empty($validated['lead_id'])) {
            Lead::forTenant($tenantId)->findOrFail($validated['lead_id']);
        }

        $validated['tenant_id'] = $tenantId;
        $validated['user_id'] = Auth::id();

        Tarefa::create($validated);

        return back()->with('success', 'Tarefa criada com sucesso.');
    }

    public function concluir(Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);

        $tarefa->update(['concluida' => true]);

        return back()->with('success', 'Tarefa concluida.');
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
