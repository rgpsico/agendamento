<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Campanha;
use App\Models\ModalCaptura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModalCapturaController extends Controller
{
    public function index()
    {
        $tenantId = $this->tenantId();
        $modals   = ModalCaptura::forTenant($tenantId)->with('campanha')->latest()->get();
        $campanhas = Campanha::forTenant($tenantId)->orderBy('nome')->get();

        return view('crm.modal-capturas.index', compact('modals', 'campanhas'));
    }

    public function store(Request $request)
    {
        $validated = $this->validar($request);
        $validated['tenant_id']   = $this->tenantId();
        $validated['origem_lead'] = $request->input('origem_lead', 'site');

        ModalCaptura::create($validated);

        return redirect()->route('crm.modal-capturas.index')->with('success', 'Widget criado com sucesso.');
    }

    public function update(Request $request, ModalCaptura $modalCaptura)
    {
        abort_unless($modalCaptura->tenant_id === $this->tenantId(), 403);

        $validated = $this->validar($request);
        $validated['origem_lead'] = $request->input('origem_lead', 'site');
        $modalCaptura->update($validated);

        return redirect()->route('crm.modal-capturas.index')->with('success', 'Widget atualizado com sucesso.');
    }

    public function destroy(ModalCaptura $modalCaptura)
    {
        abort_unless($modalCaptura->tenant_id === $this->tenantId(), 403);
        $modalCaptura->delete();

        return redirect()->route('crm.modal-capturas.index')->with('success', 'Widget excluído.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nome'             => 'required|string|max:255',
            'titulo'           => 'required|string|max:255',
            'descricao'        => 'nullable|string',
            'botao_texto'      => 'required|string|max:100',
            'cor_primaria'     => 'required|string|size:7',
            'campo_nome'       => 'boolean',
            'campo_email'      => 'boolean',
            'campo_telefone'   => 'boolean',
            'mensagem_sucesso' => 'required|string|max:255',
            'campanha_id'      => 'nullable|integer',
            'origem_lead'      => 'nullable|string|max:50',
            'ativo'            => 'boolean',
        ]) + [
            'campo_nome'     => false,
            'campo_email'    => false,
            'campo_telefone' => false,
            'ativo'          => false,
        ];
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);
        return (int) Auth::user()->empresa->id;
    }
}
