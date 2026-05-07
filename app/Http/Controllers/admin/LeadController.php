<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Lead;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%')
                  ->orWhere('email', 'like', '%' . $request->busca . '%')
                  ->orWhere('telefone', 'like', '%' . $request->busca . '%');
            });
        }

        $leads = $query->latest()->paginate(20)->withQueryString();

        $tenantId = Auth::user()?->empresa?->id;
        $emailTemplates = $tenantId
            ? EmailTemplate::where('tenant_id', $tenantId)->where('ativo', true)->orderBy('nome')->get()
            : collect();

        return view('admin.leads.index', [
            'leads'          => $leads,
            'statusList'     => Lead::$statusList,
            'origens'        => Lead::$origens,
            'emailTemplates' => $emailTemplates,
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
        $request->validate([
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

        Lead::create($request->validated());

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
        $request->validate([
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

        $lead->update($request->validated());

        return redirect()->route('admin.leads.index')->with('success', 'Lead atualizado com sucesso!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead excluído com sucesso!');
    }
}
