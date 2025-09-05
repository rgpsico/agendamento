<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotService extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'nome_servico',
        'professor',
        'horario',
        'valor',
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
