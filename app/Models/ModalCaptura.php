<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModalCaptura extends Model
{
    protected $fillable = [
        'tenant_id',
        'nome',
        'titulo',
        'descricao',
        'botao_texto',
        'cor_primaria',
        'campo_nome',
        'campo_email',
        'campo_telefone',
        'mensagem_sucesso',
        'token',
        'campanha_id',
        'origem_lead',
        'ativo',
    ];

    protected $casts = [
        'campo_nome'      => 'boolean',
        'campo_email'     => 'boolean',
        'campo_telefone'  => 'boolean',
        'ativo'           => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $modal) {
            if (empty($modal->token)) {
                $modal->token = Str::random(32);
            }
        });
    }

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function campanha()
    {
        return $this->belongsTo(Campanha::class);
    }

    public function widgetUrl(): string
    {
        return url('/widget/' . $this->token . '.js');
    }

    public function snippet(): string
    {
        $url = $this->widgetUrl();
        return '<script src="' . $url . '" defer></script>';
    }
}
