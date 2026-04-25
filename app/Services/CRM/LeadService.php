<?php

namespace App\Services\CRM;

use App\Models\Lead;
use App\Models\LeadHistorico;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public function __construct(private AutomacaoService $automacaoService)
    {
    }

    public function moverPipeline(Lead $lead, string $novoStatus, int $userId, ?string $observacao = null): Lead
    {
        $statusAnterior = $lead->pipeline_status ?: 'novo_lead';

        if ($statusAnterior === $novoStatus) {
            return $lead;
        }

        return DB::transaction(function () use ($lead, $novoStatus, $userId, $observacao, $statusAnterior) {
            $lead->update([
                'pipeline_status' => $novoStatus,
                'status' => $this->statusComercial($novoStatus),
            ]);

            LeadHistorico::create([
                'lead_id' => $lead->id,
                'de_status' => $statusAnterior,
                'para_status' => $novoStatus,
                'observacao' => $observacao,
                'user_id' => $userId,
            ]);

            $this->automacaoService->aoMoverLead($lead->fresh(), $statusAnterior, $novoStatus);

            return $lead->fresh(['historicos', 'campanha', 'responsavel']);
        });
    }

    private function statusComercial(string $pipelineStatus): string
    {
        return match ($pipelineStatus) {
            'novo_lead' => 'novo',
            'em_contato' => 'em_contato',
            'aula_experimental' => 'qualificado',
            'matriculado', 'recorrente' => 'convertido',
            default => 'novo',
        };
    }
}
