<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'inicio' => 'date',
        'fim' => 'date',
        'custo' => 'decimal:2',
        'ativo' => 'boolean',
    ];

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
}
