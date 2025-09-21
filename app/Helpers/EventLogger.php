<?php

namespace App\Helpers;

use App\Models\UserEvent;

class EventLogger
{
    /**
     * Registra um evento do usuário no banco
     *
     * @param string $eventType   Tipo do evento (ex: bot_created, service_deleted)
     * @param array  $payload     Dados adicionais (ex: ['bot_id' => 1, 'nome' => 'Teste'])
     * @param string $source      Origem do evento (ex: 'web', 'mobile', 'admin')
     */
    public static function log(string $eventType, array $payload = [], string $source = 'web'): void
    {
        $user = auth()->user();

        UserEvent::create([
            'user_id'    => $user?->id,
            'event_type' => $eventType,
            'payload'    => $payload,
            'ip'         => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'source'     => $source,
        ]);
    }
}
