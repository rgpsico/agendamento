<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campanha extends Model
{
    use HasFactory;

    protected $table = 'campanhas';

    protected $fillable = [
        'tenant_id',
        'nome',
        'canal',
        'inicio',
        'fim',
        'custo',
        'ativo',
        'public_token',
        'formulario_titulo',
        'formulario_descricao',
        'formulario_botao',
        'formulario_ativo',
    ];

    protected $casts = [
        'inicio' => 'date',
        'fim' => 'date',
        'custo' => 'decimal:2',
        'ativo' => 'boolean',
        'formulario_ativo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Campanha $campanha) {
            if (empty($campanha->public_token)) {
                $campanha->public_token = (string) Str::uuid();
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Empresa::class, 'tenant_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'campanha_id');
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function getCplAttribute(): float
    {
        $leads = $this->leads_count ?? $this->leads()->count();

        return $leads > 0 ? round(((float) $this->custo) / $leads, 2) : 0.0;
    }

    public function getFormularioTituloFinalAttribute(): string
    {
        return $this->formulario_titulo ?: $this->nome;
    }

    public function getFormularioUrlAttribute(): string
    {
        return route('public.campanhas.formulario.show', $this->public_token);
    }
}
