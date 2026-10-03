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
            $config = static::where('ativo', true)
                ->where(function ($q) use ($host) {
                    $q->where('dominio', $host)
                      ->orWhere('dominio_www', $host);
                })
                ->first();

            // admin.<dominio> abre o sistema do mesmo nicho (ex: admin.rjpasseios.com.br)
            if (! $config && self::isAdminHost($host)) {
                $base = substr($host, strlen('admin.'));
                $config = static::where('ativo', true)->where('dominio', $base)->first();
            }

            return $config;
        });
    }

    /**
     * O nicho original (pilates) usa a marca da configuração global (logo, título e capa da home).
     * Qualquer outro nicho usa só a marca própria e nunca herda a do pilates.
     */
    public function usaMarcaGlobal(): bool
    {
        return $this->nicho === 'pilates';
    }

    /** Emoji + nome para exibir quando o nicho não tem logo em imagem. */
    public function marcaTexto(): string
    {
        return trim(($this->emoji ? $this->emoji . ' ' : '') . $this->nome);
    }

    public static function isAdminHost(string $host): bool
    {
        return str_starts_with(strtolower(trim($host)), 'admin.');
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
