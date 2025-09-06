<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotLog;
use App\Models\BotService;
use App\Models\Conversation;
use App\Models\Empresa;
use App\Models\Message;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\TokenUsage;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

   public function store(Request $request)
    {
        $request->validate([
            'mensagem' => 'required|string',
            'conversation_id' => 'nullable|integer|exists:conversations,id',
            'phone' => 'nullable|string',
            'professor_id' => 'nullable|exists:usuarios,id',
        ]);

        // Define o ID do usuário (ou null se não estiver autenticado)
        $userId = auth()->check() ? auth()->id() : null;

        // Se existe conversa, busca ela; se não, cria uma nova
        if ($request->conversation_id) {
            $conversation = Conversation::find($request->conversation_id);
        } else {
            $conversation = Conversation::create([
                'empresa_id' => 1,
                'bot_id' => Bot::where('nome', 'Manicure')->first()->id ?? null,
                'user_id' => $userId,
                'mensagem' =>   'ssss',
                'telefone' => $request->phone,
            ]);
        }
      
        // Cria a mensagem do usuário
        $userMessage = Message::create([
            'from' => 'user',
            'to' => 'bot',
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'body' => $request->mensagem,
        ]);

        // Gera resposta do bot via DeepSeekService
        $botResponseText = $this->deepSeekService->generateResponse([
            'message' => $request->mensagem,
            'context' => [
                'professor_id' => $request->professor_id,
                'user_id' => $conversation->user_id,
                'services' => $request->professor_id
                    ? Servicos::where('empresa_id', 1)->get()->toArray()
                    : [],
            ],
        ]);

      

        // Salva a resposta do bot
        $botMessage = Message::create([
            'from' => 'bot',
            'to' => 'user',
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'body' => $botResponseText,
        ]);

        // Retorna a conversa e resposta do bot
        return response()->json([
            'conversation_id' => $conversation->id,
            'bot_response' => $botResponseText,
        ]);
    }


}