<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'empresa_id',
        'tokens_usados',
        'valor_cobrado',
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public static function registrarUso($botId, $empresaId, $tokensUsados, $promptTokens, $completionTokens)
    {
        return self::create([
            'bot_id' => $botId,
            'empresa_id' => $empresaId,
            'tokens_usados' => $tokensUsados,
            'valor_cobrado' => $tokensUsados * 0.0001,
            'prompt_tokens' => $promptTokens,
            'completion_tokens' => $completionTokens,
        ]);
    }
}
