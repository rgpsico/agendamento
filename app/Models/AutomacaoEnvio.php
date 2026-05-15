<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomacaoEnvio extends Model
{
    protected $table = 'automacao_envios';

    protected $fillable = [
        'sequencia_id',
        'etapa_id',
        'lead_id',
        'tenant_id',
        'canal',
        'status',
        'mensagem_enviada',
        'agendado_para',
        'enviado_em',
        'erro',
    ];

    protected $casts = [
        'agendado_para' => 'datetime',
        'enviado_em'    => 'datetime',
    ];

    public function sequencia(): BelongsTo
    {
        return $this->belongsTo(AutomacaoSequencia::class, 'sequencia_id');
    }

    public function etapa(): BelongsTo
    {
        return $this->belongsTo(AutomacaoEtapa::class, 'etapa_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pendente'  => 'Pendente',
            'enviado'   => 'Enviado',
            'falhou'    => 'Falhou',
            'cancelado' => 'Cancelado',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pendente'  => 'warning',
            'enviado'   => 'success',
            'falhou'    => 'danger',
            'cancelado' => 'secondary',
            default     => 'secondary',
        };
    }
}
