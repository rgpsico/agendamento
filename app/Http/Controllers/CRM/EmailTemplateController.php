<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::forTenant($this->tenantId())->orderBy('nome')->get();

        return view('crm.email-templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'    => 'required|string|max:255',
            'assunto' => 'required|string|max:255',
            'corpo'   => 'required|string',
            'ativo'   => 'boolean',
        ]);

        $validated['tenant_id'] = $this->tenantId();
        $validated['ativo']     = $request->boolean('ativo', true);

        EmailTemplate::create($validated);

        return redirect()->route('crm.email-templates.index')->with('success', 'Template criado com sucesso.');
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        abort_unless($emailTemplate->tenant_id === $this->tenantId(), 403);

        $validated = $request->validate([
            'nome'    => 'required|string|max:255',
            'assunto' => 'required|string|max:255',
            'corpo'   => 'required|string',
            'ativo'   => 'boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo', true);

        $emailTemplate->update($validated);

        return redirect()->route('crm.email-templates.index')->with('success', 'Template atualizado com sucesso.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        abort_unless($emailTemplate->tenant_id === $this->tenantId(), 403);

        $emailTemplate->delete();

        return redirect()->route('crm.email-templates.index')->with('success', 'Template excluído com sucesso.');
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
