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

    public function servico()
    {
        return $this->belongsTo(Servicos::class, 'servico_id'); // Assumindo 'servico_id' no pivot
    }
}
