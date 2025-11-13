<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SiteArtigo extends Model
{
    use HasFactory;

    public const STATUS_RASCUNHO = 'rascunho';
    public const STATUS_PUBLICADO = 'publicado';

    protected $table = 'site_artigos';

    protected $fillable = [
        'site_id',
        'titulo',
        'slug',
        'resumo',
        'conteudo',
        'status',
        'imagem_capa',
        'publicado_em',
    ];

    protected $casts = [
        'publicado_em' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (SiteArtigo $artigo) {
            $artigo->slug = static::criarSlug($artigo->slug ?: $artigo->titulo, $artigo->site_id);
            static::definirDataPublicacao($artigo);
        });

        static::updating(function (SiteArtigo $artigo) {
            if ($artigo->isDirty('titulo') && !$artigo->isDirty('slug')) {
                $artigo->slug = static::criarSlug($artigo->titulo, $artigo->site_id, $artigo->id);
            } elseif ($artigo->isDirty('slug')) {
                $artigo->slug = static::criarSlug($artigo->slug, $artigo->site_id, $artigo->id);
            }

            static::definirDataPublicacao($artigo);
        });
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_RASCUNHO => 'Rascunho',
            self::STATUS_PUBLICADO => 'Publicado',
        ];
    }

    public function site()
    {
        return $this->belongsTo(EmpresaSite::class, 'site_id');
    }

    protected static function criarSlug(string $base, int $siteId, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);

        if ($slug === '') {
            $slug = Str::random(8);
        }

        $original = $slug;
        $contador = 1;

        while (static::where('site_id', $siteId)
            ->where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original . '-' . $contador;
            $contador++;
        }

        return $slug;
    }

    protected static function definirDataPublicacao(SiteArtigo $artigo): void
    {
        if ($artigo->status === self::STATUS_PUBLICADO && !$artigo->publicado_em) {
            $artigo->publicado_em = now();
        }

        if ($artigo->status === self::STATUS_RASCUNHO) {
            $artigo->publicado_em = null;
        }
    }
}
