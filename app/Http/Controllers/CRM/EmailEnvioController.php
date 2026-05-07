<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Mail\LeadTemplateMail;
use App\Models\EmailTemplate;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        try {
            Mail::to($lead->email)->queue(new LeadTemplateMail($lead, $template));
            $lead->update(['email_enviado_em' => now()]);
            return back()->with('success', 'E-mail enfileirado para ' . $lead->nome . '.');
        } catch (\Exception $e) {
            Log::error('Falha ao enviar e-mail para lead ' . $lead->id . ': ' . $e->getMessage());
            return back()->with('error', 'Erro ao enviar e-mail: ' . $e->getMessage());
        }
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

        $leads = Lead::whereIn('id', $request->lead_ids)
            ->whereNotNull('email')
            ->get();

        if ($leads->isEmpty()) {
            return back()->with('error', 'Nenhum lead com e-mail encontrado na seleção.');
        }

        $enviados = 0;
        $falhas   = 0;

        foreach ($leads as $lead) {
            try {
                if (empty($lead->token)) {
                    $lead->update(['token' => Str::uuid()]);
                    $lead->refresh();
                }

                Mail::to($lead->email)->queue(new LeadTemplateMail($lead, $template));
                $lead->update(['email_enviado_em' => now()]);
                $enviados++;
            } catch (\Exception $e) {
                $falhas++;
                Log::error('Falha ao enviar e-mail para lead ' . $lead->id . ' (' . $lead->email . '): ' . $e->getMessage());
            }
        }

        if ($falhas > 0 && $enviados === 0) {
            return back()->with('error', "Falha ao enviar todos os {$falhas} e-mail(s). Verifique as configurações de e-mail em storage/logs/laravel.log.");
        }

        if ($falhas > 0) {
            return back()->with('error', "Enviados: {$enviados}. Falhas: {$falhas}. Verifique storage/logs/laravel.log para detalhes.");
        }

        return back()->with('success', "E-mail enfileirado para {$enviados} lead(s). O envio acontece em segundo plano.");
    }

    private function tenantId(): int
    {
        abort_unless(Auth::user()?->empresa, 403);

        return (int) Auth::user()->empresa->id;
    }
}
