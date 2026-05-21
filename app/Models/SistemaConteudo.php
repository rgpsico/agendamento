<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SistemaConteudo extends Model
{
    protected $table = 'sistema_conteudos';

    protected $fillable = [
        'titulo',
        'slug',
        'nicho',
        'formato',
        'topico',
        'corpo',
        'legenda',
        'hashtags',
        'status',
        'publico',
        'autor',
        'meta_descricao',
        'publicado_instagram_em',
        'publicado_tiktok_em',
        'imagem_capa',
        'palavras_count',
    ];

    protected $casts = [
        'publico'                => 'boolean',
        'publicado_instagram_em' => 'datetime',
        'publicado_tiktok_em'    => 'datetime',
    ];

    /* ── Boot: auto-slug ─────────────────────────────────────── */

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = static::gerarSlugUnico($model->titulo);
            }
        });

        static::updating(function (self $model) {
            if ($model->isDirty('titulo') && !$model->isDirty('slug')) {
                $model->slug = static::gerarSlugUnico($model->titulo, $model->id);
            }
        });
    }

    public static function gerarSlugUnico(string $titulo, ?int $ignorarId = null): string
    {
        $base = Str::slug($titulo) ?: 'conteudo';
        $slug = $base;
        $i    = 1;

        while (
            static::where('slug', $slug)
                ->when($ignorarId, fn($q) => $q->where('id', '!=', $ignorarId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /* ── Scopes ─────────────────────────────────────────────── */

    public function scopeDoNicho($query, ?string $nicho)
    {
        return $nicho ? $query->where('nicho', $nicho) : $query;
    }

    public function scopeDoFormato($query, ?string $formato)
    {
        return $formato ? $query->where('formato', $formato) : $query;
    }

    public function scopePublicados($query)
    {
        return $query->where('status', 'publicado')->where('publico', true);
    }

    /* ── Accessors ───────────────────────────────────────────── */

    public function getImagemCapaUrlAttribute(): ?string
    {
        if (!$this->imagem_capa) return null;
        return Storage::url($this->imagem_capa);
    }

    public function getUrlPublicaAttribute(): string
    {
        return url('/artigos/' . $this->slug);
    }

    public function getFormatoLabelAttribute(): string
    {
        return match($this->formato) {
            'artigo'         => 'Artigo',
            'post_instagram' => 'Post Instagram',
            'post_tiktok'    => 'Post TikTok',
            'legenda_video'  => 'Legenda de Vídeo',
            default          => ucfirst($this->formato),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'rascunho'  => 'Rascunho',
            'revisado'  => 'Revisado',
            'publicado' => 'Publicado',
            default     => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'rascunho'  => 'secondary',
            'revisado'  => 'warning',
            'publicado' => 'success',
            default     => 'secondary',
        };
    }

    public function getTempoLeituraAttribute(): string
    {
        $palavras = $this->palavras_count ?? str_word_count(strip_tags($this->corpo ?? ''));
        $minutos  = max(1, round($palavras / 200));
        return $minutos . ' min de leitura';
    }
}
