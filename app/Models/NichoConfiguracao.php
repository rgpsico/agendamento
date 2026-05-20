<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class NichoConfiguracao extends Model
{
    protected $table = 'nicho_configuracoes';

    protected $fillable = [
        'nicho',
        'nome',
        'dominio',
        'dominio_www',
        'emoji',
        'cor_primaria',
        'cor_secundaria',
        'logo',
        'login_imagem',
        'registro_imagem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Resolve a configuração pelo host da requisição.
     * Usa cache de 10 minutos para não bater no banco em toda requisição.
     */
    public static function resolveByHost(string $host): ?self
    {
        $host = strtolower(trim($host));

        return Cache::remember("nicho_config:{$host}", 600, function () use ($host) {
            return static::where('ativo', true)
                ->where(function ($q) use ($host) {
                    $q->where('dominio', $host)
                      ->orWhere('dominio_www', $host);
                })
                ->first();
        });
    }

    /**
     * Limpa o cache quando a configuração for salva.
     */
    protected static function booted(): void
    {
        static::saved(function (self $config) {
            Cache::forget("nicho_config:{$config->dominio}");
            Cache::forget("nicho_config:{$config->dominio_www}");
        });
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getLoginImagemUrlAttribute(): ?string
    {
        return $this->login_imagem ? asset('storage/' . $this->login_imagem) : null;
    }

    public function getRegistroImagemUrlAttribute(): ?string
    {
        return $this->registro_imagem ? asset('storage/' . $this->registro_imagem) : null;
    }
}
