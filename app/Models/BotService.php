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
        'servico_id'
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    public function services()
    {
        return $this->belongsToMany(Servicos::class, 'bot_services', 'bot_id', 'servico_id');
    }
}
