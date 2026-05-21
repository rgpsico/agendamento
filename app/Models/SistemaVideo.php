<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SistemaVideo extends Model
{
    protected $table = 'sistema_videos';

    protected $fillable = [
        'titulo',
        'descricao',
        'tipo',
        'url',
        'video_id',
        'arquivo',
        'thumbnail',
        'nicho',
        'categoria',
        'duracao_segundos',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // ─── Acessores ────────────────────────────────────────────────────────────

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return Storage::disk('public')->url($this->thumbnail);
        }

        return match ($this->tipo) {
            'youtube' => "https://img.youtube.com/vi/{$this->video_id}/hqdefault.jpg",
            'vimeo'   => "https://vumbnail.com/{$this->video_id}.jpg",
            default   => asset('admin/img/video-placeholder.png'),
        };
    }

    public function getEmbedUrlAttribute(): ?string
    {
        return match ($this->tipo) {
            'youtube' => "https://www.youtube.com/embed/{$this->video_id}?rel=0&autoplay=1",
            'vimeo'   => "https://player.vimeo.com/video/{$this->video_id}?autoplay=1",
            'upload'  => $this->arquivo ? Storage::disk('public')->url($this->arquivo) : null,
            default   => null,
        };
    }

    public function getLinkCompartilharAttribute(): string
    {
        return match ($this->tipo) {
            'youtube' => "https://youtu.be/{$this->video_id}",
            'vimeo'   => "https://vimeo.com/{$this->video_id}",
            'upload'  => $this->arquivo ? Storage::disk('public')->url($this->arquivo) : '',
            default   => $this->url ?? '',
        };
    }

    public function getDuracaoFormatadaAttribute(): string
    {
        if (!$this->duracao_segundos) return '';
        $m = intdiv($this->duracao_segundos, 60);
        $s = $this->duracao_segundos % 60;
        return sprintf('%d:%02d', $m, $s);
    }

    // ─── Helpers estáticos ───────────────────────────────────────────────────

    public static function extrairVideoId(string $url, string $tipo): ?string
    {
        return match ($tipo) {
            'youtube' => self::extrairYoutubeId($url),
            'vimeo'   => self::extrairVimeoId($url),
            default   => null,
        };
    }

    private static function extrairYoutubeId(string $url): ?string
    {
        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $url,
            $matches
        );
        return $matches[1] ?? null;
    }

    private static function extrairVimeoId(string $url): ?string
    {
        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeDoNicho($query, ?string $nicho)
    {
        if ($nicho) {
            return $query->where(fn($q) => $q->where('nicho', $nicho)->orWhereNull('nicho'));
        }
        return $query;
    }
}
