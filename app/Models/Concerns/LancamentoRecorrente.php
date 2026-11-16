<?php

namespace App\Models\Concerns;

use App\Support\Recorrencia;

/**
 * Receitas e despesas recorrentes: quantas vezes acontecem num período e quanto somam.
 * Ex.: aluguel mensal de R$ 1.200 num filtro de 3 meses = 3 vezes = R$ 3.600.
 */
trait LancamentoRecorrente
{
    /** Ativas (não marcadas como INACTIVE) com vigência que encosta no período. */
    public function scopeNoPeriodo($query, string $inicio, string $fim)
    {
        $tabela = $query->getModel()->getTable();

        return $query
            ->where(fn ($q) => $q->whereNull("{$tabela}.status")->orWhere("{$tabela}.status", '!=', 'INACTIVE'))
            ->whereDate("{$tabela}.data_inicio", '<=', $fim)
            ->where(fn ($q) => $q->whereNull("{$tabela}.data_fim")->orWhereDate("{$tabela}.data_fim", '>=', $inicio));
    }

    public function vezesNoPeriodo(string $inicio, string $fim): int
    {
        if (strtoupper((string) $this->status) === 'INACTIVE') {
            return 0;
        }

        return Recorrencia::vezes($this->data_inicio, $this->data_fim, $this->frequencia, $inicio, $fim);
    }

    public function valorNoPeriodo(string $inicio, string $fim): float
    {
        return round((float) $this->getRawOriginal('valor') * $this->vezesNoPeriodo($inicio, $fim), 2);
    }
}
