<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Quantas vezes um lançamento recorrente (receita ou despesa) acontece dentro de um período.
 *
 * A primeira ocorrência é a data de início; as seguintes vêm a cada dia/semana/mês/ano
 * (mensal no mesmo dia do mês, ajustado para o último dia quando o mês é mais curto: início 31/01 -> 28/02).
 * Para na data de fim, quando houver.
 */
class Recorrencia
{
    public const FREQUENCIAS = ['DIARIA', 'SEMANAL', 'MENSAL', 'ANUAL'];

    /** Datas das ocorrências dentro de [$periodoInicio, $periodoFim] (inclusive). */
    public static function datas(
        $inicio,
        $fim,
        ?string $frequencia,
        CarbonInterface|string $periodoInicio,
        CarbonInterface|string $periodoFim
    ): array {
        if (! $inicio) {
            return [];
        }

        $inicio = Carbon::parse($inicio)->startOfDay();
        $periodoInicio = Carbon::parse($periodoInicio)->startOfDay();
        $periodoFim = Carbon::parse($periodoFim)->startOfDay();
        $limite = $fim ? Carbon::parse($fim)->startOfDay()->min($periodoFim) : $periodoFim;

        if ($limite->lt($periodoInicio) || $inicio->gt($limite)) {
            return [];
        }

        $frequencia = strtoupper((string) $frequencia);
        $frequencia = $frequencia === 'DIARIO' ? 'DIARIA' : $frequencia; // receitas_recorrentes usa "diario"
        $datas = [];

        // Salta direto para perto do início do período (evita iterar anos de ocorrências passadas).
        $n = self::passosAte($inicio, $periodoInicio, $frequencia);

        for ($guarda = 0; $guarda < 5000; $guarda++, $n++) {
            $data = self::ocorrencia($inicio, $n, $frequencia);
            if ($data === null || $data->gt($limite)) {
                break;
            }
            if ($data->gte($periodoInicio)) {
                $datas[] = $data;
            }
            if ($frequencia === '' || ! in_array($frequencia, self::FREQUENCIAS, true)) {
                break; // frequência desconhecida: conta só uma vez
            }
        }

        return $datas;
    }

    public static function vezes($inicio, $fim, ?string $frequencia, $periodoInicio, $periodoFim): int
    {
        return count(self::datas($inicio, $fim, $frequencia, $periodoInicio, $periodoFim));
    }

    /** n-ésima ocorrência (0 = data de início). */
    private static function ocorrencia(Carbon $inicio, int $n, string $frequencia): ?Carbon
    {
        return match ($frequencia) {
            'DIARIA'  => $inicio->copy()->addDays($n),
            'SEMANAL' => $inicio->copy()->addWeeks($n),
            'MENSAL'  => $inicio->copy()->addMonthsNoOverflow($n),
            'ANUAL'   => $inicio->copy()->addYearsNoOverflow($n),
            default   => $n === 0 ? $inicio->copy() : null,
        };
    }

    /** Quantos passos inteiros cabem entre o início e o começo do período (sem passar dele). */
    private static function passosAte(Carbon $inicio, Carbon $periodoInicio, string $frequencia): int
    {
        if ($periodoInicio->lte($inicio)) {
            return 0;
        }

        $n = match ($frequencia) {
            'DIARIA'  => (int) $inicio->diffInDays($periodoInicio),
            'SEMANAL' => intdiv((int) $inicio->diffInDays($periodoInicio), 7),
            'MENSAL'  => (int) $inicio->diffInMonths($periodoInicio),
            'ANUAL'   => (int) $inicio->diffInYears($periodoInicio),
            default   => 0,
        };

        // margem de segurança para meses curtos (ex.: dia 31)
        return max(0, $n - 1);
    }
}
