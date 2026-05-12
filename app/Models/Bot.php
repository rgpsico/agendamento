<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bot extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'segmento',
        'tom',
        'status',
        'token_deepseek',
        'prompt',
        // Widget
        'widget_token',
        'widget_ativo',
        'widget_cor',
        'widget_posicao',
        'widget_saudacao',
        'widget_avatar_url',
        'widget_nome_bot',
    ];

    protected $casts = [
        'status'       => 'boolean',
        'widget_ativo' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $bot) {
            if (empty($bot->widget_token)) {
                $bot->widget_token = Str::random(40);
            }
        });
    }

    public function widgetUrl(): string
    {
        return url('/widget/bot/' . $this->widget_token . '.js');
    }

    public function widgetSnippet(): string
    {
        return '<script src="' . $this->widgetUrl() . '" defer></script>';
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function logs()
    {
        return $this->hasMany(BotLog::class);
    }

    public function tokenUsages()
    {
        return $this->hasMany(TokenUsage::class);
    }

    public function services()
    {
        return $this->belongsToMany(Servicos::class, 'bot_services', 'bot_id', 'servico_id');
    }
}
