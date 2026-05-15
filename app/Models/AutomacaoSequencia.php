<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomacaoSequencia extends Model
{
    protected $table = 'automacao_sequencias';

    protected $fillable = [
        'tenant_id',
        'nome',
        'descricao',
        'ativo',
        'gatilho',
        'gatilho_valor',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function etapas(): HasMany
    {
        return $this->hasMany(AutomacaoEtapa::class, 'sequencia_id')->orderBy('ordem');
    }

    public function envios(): HasMany
    {
        return $this->hasMany(AutomacaoEnvio::class, 'sequencia_id');
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeAtiva($query)
    {
        return $query->where('ativo', true);
    }

    /** Retorna sequências que devem disparar para um determinado pipeline_status */
    public static function paraPipelineStatus(int $tenantId, string $status)
    {
        return static::where('tenant_id', $tenantId)
            ->where('ativo', true)
            ->where('gatilho', 'pipeline_status')
            ->where('gatilho_valor', $status)
            ->with('etapas')
            ->get();
    }

    /** Retorna sequências com gatilho 'novo_lead' */
    public static function paraNovoLead(int $tenantId)
    {
        return static::where('tenant_id', $tenantId)
            ->where('ativo', true)
            ->where('gatilho', 'novo_lead')
            ->with('etapas')
            ->get();
    }

    public function getGatilhoLabelAttribute(): string
    {
        return match ($this->gatilho) {
            'manual'          => 'Manual',
            'pipeline_status' => 'Mudança de Status: ' . (Lead::$pipelineStatus[$this->gatilho_valor] ?? $this->gatilho_valor),
            'novo_lead'       => 'Novo Lead',
            default           => $this->gatilho,
        };
    }

    public function getTotalEnviosAttribute(): int
    {
        return $this->envios()->where('status', 'enviado')->count();
    }
}
