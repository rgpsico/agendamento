<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;

class BotWidgetController extends Controller
{
    public function __construct(protected DeepSeekService $deepSeekService) {}

    /**
     * POST /api/bot/{token}/chat
     * Endpoint público — sem auth, sem CSRF.
     */
    public function chat(Request $request, string $token)
    {
        $bot = Bot::where('widget_token', $token)
                  ->where('widget_ativo', true)
                  ->where('status', true)
                  ->first();

        if (! $bot) {
            return response()->json(['error' => 'Bot não encontrado ou inativo.'], 404);
        }

        $request->validate([
            'message'         => 'required|string|max:1000',
            'conversation_id' => 'nullable|integer',
        ]);

        $userMessage    = $request->input('message');
        $conversationId = $request->input('conversation_id');

        // Pega ou cria conversa
        $conversation = $conversationId
            ? \App\Models\Conversation::find($conversationId)
            : null;

        if (! $conversation) {
            $conversation = \App\Models\Conversation::createWithBot($bot, null, null, $bot->empresa_id);
        }

        try {
            // Salva mensagem do usuário
            $conversation->messages()->create([
                'from'  => 'user',
                'to'    => 'bot',
                'body'  => $userMessage,
                'tipo'  => 'user',
            ]);

            // Consulta DeepSeek
            $result = $this->deepSeekService->getDeepSeekResponseWithPrompt(
                $bot,
                $userMessage,
                $conversation,
                $bot->empresa_id
            );

            $reply = $result['reply'];

            // Salva resposta do bot
            $conversation->messages()->create([
                'from' => 'bot',
                'to'   => 'user',
                'role' => 'assistant',
                'body' => $reply,
            ]);

            return response()->json([
                'conversation_id' => $conversation->id,
                'reply'           => $reply,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao processar resposta: ' . $e->getMessage(),
            ], 500);
        }
    }
}
