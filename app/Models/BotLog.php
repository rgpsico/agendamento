<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'mensagem_usuario',
        'resposta_bot',
        'tokens_usados',
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
