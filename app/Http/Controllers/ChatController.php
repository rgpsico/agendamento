<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotLog;
use App\Models\BotService;
use App\Models\Conversation;
use App\Models\Empresa;
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
            'conversation_id' => 'nullable|string',
            'professor_id' => 'nullable|exists:usuarios,id', // Corrigido de 'users' para 'usuarios'
        ]);

        // Create user message
        $conversation = Conversation::create([
            'conversation_id' => $request->conversation_id ?? uniqid('conv_'),
            'user_id' => 1,
            'mensagem' => $request->mensagem,
            'tipo' => 'user',
            'empresa_id' => 1,
            'bot_id' => Bot::where('nome', 'BookingAssistant')->first()->id ?? null, // Ajuste conforme necessário
        ]);

        // Generate bot response using DeepSeekService
        $botResponse = $this->deepSeekService->generateResponse([
            'message' => $request->mensagem,
            'context' => [
                'professor_id' => $request->professor_id,
                'user_id' => 1,
                'services' => $request->professor_id ? Servicos::where('empresa_id', $request->professor_id)->get()->toArray() : [],
            ],
        ]);

  

        // Store bot response
        Conversation::create([
            'conversation_id' => $conversation->conversation_id,
            'user_id' => null, // Bot response
            'mensagem' => $botResponse,
            'tipo' => 'bot',
            'empresa_id' => $conversation->empresa_id,
            'bot_id' => $conversation->bot_id,
        ]);

        return response()->json([
            'conversation_id' => $conversation->conversation_id,
            'bot_response' => $botResponse,
        ]);
    }
}