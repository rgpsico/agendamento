<?php

namespace App\Services\CRM;

use App\Models\Lead;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class AutomacaoService
{
    public function __construct(private TwilioService $twilioService)
    {
    }

    public function aoMoverLead(Lead $lead, string $statusAnterior, string $novoStatus): void
    {
        if (! $lead->telefone || ! in_array($novoStatus, ['em_contato', 'aula_experimental'], true)) {
            return;
        }

        $mensagem = match ($novoStatus) {
            'em_contato' => "Ola {$lead->nome}, recebemos seu interesse e vamos falar com voce para entender seu objetivo.",
            'aula_experimental' => "Ola {$lead->nome}, sua aula experimental esta quase pronta para ser agendada.",
            default => null,
        };

        if (! $mensagem) {
            return;
        }

        try {
            $this->twilioService->sendWhatsApp($this->formatPhone($lead->telefone), $mensagem);
        } catch (\Throwable $e) {
            Log::warning('CRM: falha ao enviar WhatsApp automatico', [
                'lead_id' => $lead->id,
                'status_anterior' => $statusAnterior,
                'novo_status' => $novoStatus,
                'erro' => $e->getMessage(),
            ]);
        }
    }

    private function formatPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        return str_starts_with($digits, '55') ? $digits : '55' . $digits;
    }
}
