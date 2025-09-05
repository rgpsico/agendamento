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
}
