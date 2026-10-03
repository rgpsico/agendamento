<?php

namespace App\Http\Controllers;

use App\Models\LeadQualificacao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Wizard público que o lead responde pelo link enviado (sem login). */
class ValidadorLeadController extends Controller
{
    public function show(string $token)
    {
        $q = LeadQualificacao::where('token', $token)->firstOrFail();

        $nicho = app()->has('currentNicho') ? app('currentNicho') : null;

        return view($q->respondido() ? 'validador.obrigado' : 'validador.wizard', [
            'q'      => $q,
            'marca'  => $nicho?->nome ?? 'PilatesGestão',
            'emoji'  => $nicho?->emoji ?? '🧘',
            'corA'   => $nicho?->cor_primaria ?? '#059669',
            'corB'   => $nicho?->cor_secundaria ?? '#0f172a',
            'logo'   => $nicho?->logo_url,
            'nome'   => $q->nome ?: $q->lead?->nome,
            'whatsapp' => $q->whatsapp ?: $q->lead?->telefone,
        ]);
    }

    public function responder(Request $request, string $token): JsonResponse
    {
        $q = LeadQualificacao::where('token', $token)->firstOrFail();

        if ($q->respondido()) {
            return response()->json(['ok' => true, 'ja_respondido' => true]);
        }

        $data = $request->validate([
            'nome'                   => 'required|string|max:120',
            'cidade'                 => 'required|string|max:120',
            'nicho'                  => ['required', Rule::in(array_keys(LeadQualificacao::NICHOS))],
            'nicho_outro'            => 'required_if:nicho,outro|nullable|string|max:120',
            'tem_google_meu_negocio' => ['required', Rule::in(array_keys(LeadQualificacao::GOOGLE))],
            'ja_fez_campanha'        => ['required', Rule::in(array_keys(LeadQualificacao::CAMPANHA))],
            'nivel_digital'          => ['required', Rule::in(array_keys(LeadQualificacao::NIVEL))],
            'sobre_negocio'          => 'required|string|min:10|max:2000',
            'whatsapp'               => 'nullable|string|max:30',
            'observacao'             => 'nullable|string|max:1000',
        ]);

        if (($data['nicho'] ?? null) !== 'outro') {
            $data['nicho_outro'] = null;
        }

        $q->fill($data)->forceFill([
            'status'        => 'respondido',
            'respondido_em' => now(),
            'ip'            => $request->ip(),
            'user_agent'    => mb_substr((string) $request->userAgent(), 0, 255),
        ])->save();

        $this->anexarAoLead($q);

        return response()->json(['ok' => true]);
    }

    /** Deixa um resumo nas observações do lead pra aparecer no CRM. */
    private function anexarAoLead(LeadQualificacao $q): void
    {
        $lead = $q->lead;
        if (! $lead) {
            return;
        }

        $resumo = sprintf(
            "[Validador %s] %s, de %s. Nicho: %s. Google Meu Negócio: %s. Campanha: %s. Nível digital: %s. Sobre o negócio: %s%s",
            now()->format('d/m/Y'),
            $q->nome,
            $q->cidade,
            $q->nichoRotulo(),
            LeadQualificacao::GOOGLE[$q->tem_google_meu_negocio] ?? '-',
            LeadQualificacao::CAMPANHA[$q->ja_fez_campanha] ?? '-',
            LeadQualificacao::NIVEL[$q->nivel_digital] ?? '-',
            $q->sobre_negocio,
            $q->observacao ? " Obs: {$q->observacao}" : ''
        );

        $lead->update([
            'observacoes' => trim(($lead->observacoes ? $lead->observacoes . "\n\n" : '') . $resumo),
        ]);
    }
}
