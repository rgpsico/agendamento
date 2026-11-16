<?php

namespace App\Models\Concerns;

use App\Support\EmpresasDoUsuario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Isola o registro por empresa (multi-tenant) para quem está logado no painel/API.
 *
 * - Toda consulta só enxerga registros das empresas do usuário logado (as que ele possui + a em que é professor).
 *   Usuário logado sem empresa não enxerga nada.
 * - Ao criar, o empresa_id é preenchido com a empresa do usuário; um empresa_id de outra empresa
 *   (ex.: campo oculto do formulário alterado) é trocado pela empresa do usuário.
 * - Sem usuário logado (webhooks, filas, artisan) nada muda: o código chamador define o empresa_id.
 *
 * Modelos com $empresaOpcional = true também enxergam registros com empresa_id nulo (padrões globais).
 */
trait DaEmpresaLogada
{
    /** @var array<int, int[]> cache por usuário durante a requisição */
    private static array $empresasPorUsuario = [];

    public static function bootDaEmpresaLogada(): void
    {
        static::addGlobalScope('empresa_logada', function (Builder $query) {
            $ids = static::empresasLogadas();
            if ($ids === null) {
                return;
            }

            $coluna = $query->getModel()->qualifyColumn('empresa_id');
            $opcional = property_exists(static::class, 'empresaOpcional') && static::$empresaOpcional;

            $query->where(function (Builder $q) use ($coluna, $ids, $opcional) {
                $q->whereIn($coluna, $ids ?: [0]);
                if ($opcional) {
                    $q->orWhereNull($coluna);
                }
            });
        });

        static::creating(function ($model) {
            $ids = static::empresasLogadas();
            if ($ids === null) {
                return;
            }

            abort_if(empty($ids), 403, 'Empresa não identificada.');

            if (! in_array((int) $model->empresa_id, $ids, true)) {
                $model->empresa_id = $ids[0];
            }
        });

        // Impede mover um registro para outra empresa numa edição.
        static::updating(function ($model) {
            $ids = static::empresasLogadas();
            if ($ids === null || ! $model->isDirty('empresa_id')) {
                return;
            }

            if (! in_array((int) $model->empresa_id, $ids, true)) {
                $model->empresa_id = $model->getOriginal('empresa_id');
            }
        });
    }

    /** Ids das empresas do usuário logado, ou null quando não há usuário logado. */
    protected static function empresasLogadas(): ?array
    {
        $usuario = Auth::user();
        if (! $usuario) {
            return null;
        }

        return self::$empresasPorUsuario[$usuario->getKey()] ??= EmpresasDoUsuario::ids($usuario);
    }
}
