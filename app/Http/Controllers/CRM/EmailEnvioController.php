<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Mail\LeadTemplateMail;
use App\Models\EmailTemplate;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailEnvioController extends Controller
{
    public function enviar(Request $request, Lead $lead)
    {
        abort_unless($lead->tenant_id === $this->tenantId(), 403);
        abort_unless($lead->email, 422);

        $request->validate([
            'email_template_id' => 'required|integer',
        ]);

        $template = EmailTemplate::forTenant($this->tenantId())
            ->where('ativo', true)
            ->findOrFail($request->email_template_id);

        if (empty($lead->token)) {
            $lead->update(['token' => Str::uuid()]);
            $lead->refresh();
        }

        Mail::to($lead->email)->send(new LeadTemplateMail($lead, $template));
        $lead->update(['email_enviado_em' => now()]);

        return back()->with('success', 'E-mail enviado para ' . $lead->nome . '.');
    }

    public function enviarMassa(Request $request)
    {
        $request->validate([
            'email_template_id' => 'required|integer',
            'lead_ids'          => 'required|array|min:1',
            'lead_ids.*'        => 'integer',
        ]);

        $tenantId = $this->tenantId();

        $template = EmailTemplate::forTenant($tenantId)
            ->where('ativo', true)
            ->findOrFail($request->email_template_id);

        $leads = Lead::forTenant($tenantId)
            ->whereIn('id', $request->lead_ids)
            ->whereNotNull('email')
            ->get();

        $enviados = 0;

        foreach ($leads as $lead) {
            if (empty($lead->token)) {
                $lead->update(['token' => Str::uuid()]);
                $lead->refresh();
            }

            Mail::to($lead->email)->send(new LeadTemplateMail($lead, $template));
            $lead->update(['email_enviado_em' => now()]);
            $enviados++;
        }

        return back()->with('success', "E-mail enviado para {$enviados} lead(s) com sucesso.");
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
