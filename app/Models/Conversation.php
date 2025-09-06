<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'bot_id',
        'user_id',
        'mensagem',
        'tipo'
    
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

      public function messages()
    {
        return $this->hasMany(Message::class);
    }


    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    public function user()
    {
        return $this->belongsTo(Usuario::class);
    }
}
