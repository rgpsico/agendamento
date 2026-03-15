<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'from',
        'to',
        'role',
        'body',
        'twilio_sid',
        'conversation_id'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public static function createUserMessage($conversationId, $body)
    {
        return self::create([
            'from' => 'user',
            'to' => 'bot',
            'conversation_id' => $conversationId,
            'role' => 'user',
            'body' => $body,
        ]);
    }

    /**
     * Cria uma mensagem de bot.
     */
    public static function createBotMessage($conversationId, $body)
    {
        return self::create([
            'from' => 'bot',
            'to' => 'user',
            'conversation_id' => $conversationId,
            'role' => 'assistant',
            'body' => $body,
        ]);
    }
}
