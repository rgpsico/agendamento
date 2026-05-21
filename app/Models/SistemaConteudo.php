<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SistemaConteudo extends Model
{
    protected $table = 'sistema_conteudos';

    protected $fillable = [
        'titulo',
        'nicho',
        'formato',
        'topico',
        'corpo',
        'legenda',
        'hashtags',
        'status',
        'publicado_instagram_em',
        'publicado_tiktok_em',
        'imagem_capa',
        'palavras_count',
    ];

    protected $casts = [
        'publicado_instagram_em' => 'datetime',
        'publicado_tiktok_em'    => 'datetime',
    ];

    /* ── Scopes ─────────────────────────────────────── */

    public function scopeDoNicho($query, ?string $nicho)
    {
        if ($nicho) {
            return $query->where('nicho', $nicho);
        }
        return $query;
    }

    public function scopeDoFormato($query, ?string $formato)
    {
        if ($formato) {
            return $query->where('formato', $formato);
        }
        return $query;
    }

    /* ── Accessors ───────────────────────────────────── */

    public function getImagemCapaUrlAttribute(): ?string
    {
        if (!$this->imagem_capa) return null;
        return \Illuminate\Support\Facades\Storage::url($this->imagem_capa);
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
}
