<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id', 
        'nome', 
        'segmento', 
        'tom', 
        'status', 
        'token_deepseek'
    ];

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
        return $this->hasMany(BotService::class);
    }
    
}
