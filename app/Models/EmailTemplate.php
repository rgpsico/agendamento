<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'tenant_id',
        'nome',
        'assunto',
        'corpo',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function renderCorpo(Lead $lead): string
    {
        return str_replace(
            ['{nome}', '{email}', '{telefone}', '{empresa}', '{interesse}'],
            [$lead->nome, $lead->email ?? '', $lead->telefone ?? '', $lead->empresa ?? '', $lead->interesse ?? ''],
            $this->corpo
        );
    }

    public function renderAssunto(Lead $lead): string
    {
        return str_replace(
            ['{nome}', '{email}', '{telefone}', '{empresa}', '{interesse}'],
            [$lead->nome, $lead->email ?? '', $lead->telefone ?? '', $lead->empresa ?? '', $lead->interesse ?? ''],
            $this->assunto
        );
    }
}
