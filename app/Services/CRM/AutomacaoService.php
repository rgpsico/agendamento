<?php

namespace App\Services\CRM;

use App\Jobs\EnviarEtapaSequenciaJob;
use App\Models\AutomacaoEnvio;
use App\Models\AutomacaoSequencia;
use App\Models\Lead;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class AutomacaoService
{
    public function __construct(private TwilioService $twilioService)
    {
    }

    // ─── Gatilho de pipeline ─────────────────────────────────────────────────

    /**
     * Chamado sempre que um lead muda de pipeline_status.
     * 1. Envia mensagem rápida fixa (comportamento original).
     * 2. Dispara sequências de automação configuradas para o novo status.
     */
    public function aoMoverLead(Lead $lead, string $statusAnterior, string $novoStatus): void
    {
        $this->enviarMensagemRapida($lead, $novoStatus);
        $this->dispararSequenciasPorPipeline($lead, $novoStatus);
    }

    // ─── Mensagem rápida (comportamento original) ────────────────────────────

    private function enviarMensagemRapida(Lead $lead, string $novoStatus): void
    {
        if (! $lead->telefone || ! in_array($novoStatus, ['em_contato', 'aula_experimental'], true)) {
            return;
        }

        $mensagem = match ($novoStatus) {
            'em_contato'        => "Ola {$lead->nome}, recebemos seu interesse e vamos falar com voce para entender seu objetivo.",
            'aula_experimental' => "Ola {$lead->nome}, sua aula experimental esta quase pronta para ser agendada.",
            default             => null,
        };

        if (! $mensagem) {
            return;
        }

        try {
            $this->twilioService->sendWhatsApp($this->formatPhone($lead->telefone), $mensagem);
        } catch (\Throwable $e) {
            Log::warning('CRM: falha ao enviar WhatsApp automatico', [
                'lead_id'         => $lead->id,
                'status_anterior' => $statusAnterior,
                'novo_status'     => $novoStatus,
                'erro'            => $e->getMessage(),
            ]);
        }
    }

    // ─── Sequências automáticas por pipeline ─────────────────────────────────

    private function dispararSequenciasPorPipeline(Lead $lead, string $novoStatus): void
    {
        $sequencias = AutomacaoSequencia::paraPipelineStatus(
            $lead->tenant_id ? (int) $lead->tenant_id : null,
            $novoStatus,
            $lead->origem ?? null  // nicho para leads do super admin
        );

        foreach ($sequencias as $sequencia) {
            $this->iniciarSequencia($lead, $sequencia);
        }
    }

    /**
     * Dispara uma sequência completa para um lead.
     * Agenda um Job para cada etapa × canal com o delay correto.
     */
    public function iniciarSequencia(Lead $lead, AutomacaoSequencia $sequencia): void
    {
        if ($sequencia->etapas->isEmpty()) {
            return;
        }

        $base = now();

        foreach ($sequencia->etapas as $etapa) {
            $enviarEm = $etapa->calcularEnvioEm($base);
            $canais   = $etapa->canal === 'ambos' ? ['whatsapp', 'email'] : [$etapa->canal];

            foreach ($canais as $canal) {
                if ($canal === 'whatsapp' && empty($lead->telefone)) {
                    continue;
                }
                if ($canal === 'email' && empty($lead->email)) {
                    continue;
                }

                // Evita duplicata
                $jaExiste = AutomacaoEnvio::where('etapa_id', $etapa->id)
                    ->where('lead_id', $lead->id)
                    ->where('canal', $canal)
                    ->exists();

                if ($jaExiste) {
                    continue;
                }

                $envio = AutomacaoEnvio::create([
                    'sequencia_id'  => $sequencia->id,
                    'etapa_id'      => $etapa->id,
                    'lead_id'       => $lead->id,
                    'tenant_id'     => $lead->tenant_id,
                    'canal'         => $canal,
                    'status'        => 'pendente',
                    'agendado_para' => $enviarEm,
                ]);

                EnviarEtapaSequenciaJob::dispatch($envio->id)
                    ->delay($enviarEm);
            }

            // Próximo delay conta a partir desta etapa
            $base = $enviarEm;
        }

        Log::info("AutomacaoIA: sequência #{$sequencia->id} iniciada para lead #{$lead->id}", [
            'lead'      => $lead->nome,
            'sequencia' => $sequencia->nome,
            'etapas'    => $sequencia->etapas->count(),
        ]);
    }

    /**
     * Cancela todos os envios pendentes de um lead (ex: ao converter).
     */
    public function cancelarTodosOsEnvios(Lead $lead): int
    {
        return AutomacaoEnvio::where('lead_id', $lead->id)
            ->where('status', 'pendente')
            ->update(['status' => 'cancelado']);
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function formatPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        return str_starts_with($digits, '55') ? $digits : '55' . $digits;
    }
}
