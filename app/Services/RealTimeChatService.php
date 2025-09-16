<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RealTimeChatService
{
    protected $nodeServer;

    public function __construct()
    {
        // URL do seu servidor Node.js
        $this->nodeServer = env('NODE_SERVER_URL', 'https://www.comunidadeppg.com.br:3000');
    }

    /**
     * Envia uma mensagem para uma conversa específica no Node.js
     *
     * @param int $conversationId
     * @param int|string $userId
     * @param string $mensagem
     * @return array|null
     */
    public function sendMessage($conversationId, $userId, $mensagem)
    {
        try {
            $response = Http::post("{$this->nodeServer}/chatmessage", [
                'conversation_id' => $conversationId,
                'user_id' => $userId,
                'mensagem' => $mensagem
            ]);

            return $response->json();
        } catch (\Exception $e) {
            \Log::error("Erro ao enviar mensagem para Node.js: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Envia uma mensagem para entregadores, por exemplo
     */
    public function sendToEntregadores($payload)
    {
        try {
            $response = Http::post("{$this->nodeServer}/enviarpedidoparaentregadores", $payload);
            return $response->json();
        } catch (\Exception $e) {
            \Log::error("Erro ao enviar pedido para entregadores: " . $e->getMessage());
            return null;
        }
    }


    public function enviarRealTime(array $payload)
    {
        try {
            $response = Http::post('https://www.comunidadeppg.com.br:3000/chatmessage', [
                'conversation_id' => $payload['conversation_id'] ?? null,
                'user_id' => $payload['user_id'] ?? 'guest',
                'mensagem' => $payload['mensagem'] ?? '',
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Erro ao enviar mensagem em tempo real: " . $e->getMessage());
            return null;
        }
    }
}
