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

    // Busca ou cria a conversa
    if ($request->conversation_id) {
        $conversation = Conversation::find($request->conversation_id);
    } else {
        $bot = Bot::where('nome', 'Manicure')->first();
        $conversation = Conversation::create([
            'empresa_id' => 1,
            'bot_id' => $bot->id ?? null,
            'user_id' => $userId,
            'mensagem' => 'Início da conversa',
            'telefone' => $request->phone,
        ]);
    }

    // Sanitiza e salva a mensagem do usuário
    $cleanUserMessage = $this->sanitizeMessage($request->mensagem);
    $userMessage = Message::create([
        'from' => 'user',
        'to' => 'bot',
        'conversation_id' => $conversation->id,
        'role' => 'user',
        'body' => $cleanUserMessage
    ]);

    // Enviar alerta para a empresa via Twilio
    $empresa = $conversation->empresa;
    if ($empresa && $empresa->telefone) {
        $userMessage->load('conversation');
        app(\App\Services\TwilioService::class)
            ->enviarAlertaNovaMensagem($userMessage, $empresa);
    }

    // Gera resposta do bot
    // Descomentar quando o serviço DeepSeek estiver ativo
    // $botResponseText = $this->deepSeekService->getDeepSeekResponse(
    //     $conversation->bot,
    //     $request->mensagem,
    //     $conversation->empresa_id
    // );
    $botResponseText = 'Entendido! Como posso ajuaaaadar mais?'; // Resposta padrão temporária

    // Sanitiza e salva a resposta do bot
    $cleanBotResponse = $this->sanitizeMessage($botResponseText);
    $botMessage = Message::create([
        'from' => 'bot',
        'to' => 'user',
        'conversation_id' => $conversation->id,
        'role' => 'assistant',
        'body' => $cleanBotResponse
    ]);

    // Enviar apenas a resposta do bot para o servidor Socket.IO
    Http::post('https://www.comunidadeppg.com.br:3000/chatmessage', [
        'conversation_id' => $conversation->id,
        'user_id' => null,
        'from' => 'bot',
        'mensagem' => $cleanBotResponse,
    ]);

    \Log::info('Enviando resposta do bot para Socket.IO:', [
        'conversation_id' => $conversation->id,
        'from' => 'bot',
        'mensagem' => $cleanBotResponse
    ]);

    return response()->json([
        'conversation_id' => $conversation->id,
        'bot_response' => $cleanBotResponse,
    ]);
}



 public function enviarparabatepaposite(Request $request)
{
    $request->validate([
        'mensagem' => 'required|string',
        'conversation_id' => 'nullable|integer|exists:conversations,id',
        'phone' => 'nullable|string',
        'professor_id' => 'nullable|exists:usuarios,id',
    ]);

    // Define o ID do usuário (ou null se não estiver autenticado)
    $userId = auth()->check() ? auth()->id() : null;

    // Busca ou cria a conversa
    if ($request->conversation_id) {
        $conversation = Conversation::find($request->conversation_id);
    } else {
        $bot = Bot::where('nome', 'Manicure')->first();
        $conversation = Conversation::create([
            'empresa_id' => 1,
            'bot_id' => $bot->id ?? null,
            'user_id' => $userId,
            'mensagem' => 'Início da conversa',
            'telefone' => $request->phone,
        ]);
    }

    // Sanitiza e salva a mensagem do usuário
    $cleanUserMessage = $this->sanitizeMessage($request->mensagem);
    $userMessage = Message::create([
        'from' => 'user',
        'to' => 'bot',
        'conversation_id' => $conversation->id,
        'role' => 'user',
        'body' => $cleanUserMessage
    ]);

    // Enviar alerta para a empresa via Twilio
    $empresa = $conversation->empresa;
    if ($empresa && $empresa->telefone) {
        $userMessage->load('conversation');
        app(\App\Services\TwilioService::class)
            ->enviarAlertaNovaMensagem($userMessage, $empresa);
    }

    // Gera resposta do bot
    // Descomentar quando o serviço DeepSeek estiver ativo
    // $botResponseText = $this->deepSeekService->getDeepSeekResponse(
    //     $conversation->bot,
    //     $request->mensagem,
    //     $conversation->empresa_id
    // );
    $botResponseText = 'Entendido! Como posso ajuaaaadar mais?'; // Resposta padrão temporária

    // Sanitiza e salva a resposta do bot
    $cleanBotResponse = $this->sanitizeMessage($botResponseText);
    $botMessage = Message::create([
        'from' => 'bot',
        'to' => 'user',
        'conversation_id' => $conversation->id,
        'role' => 'assistant',
        'body' => $cleanBotResponse
    ]);

    // Enviar apenas a resposta do bot para o servidor Socket.IO
    Http::post('https://www.comunidadeppg.com.br:3000/enviarparaosass', [
        'conversation_id' => $conversation->id,
        'user_id' => null,
        'from' => 'bot',
        'mensagem' => $cleanBotResponse,
    ]);

    \Log::info('Enviando resposta do bot para Socket.IO:', [
        'conversation_id' => $conversation->id,
        'from' => 'bot',
        'mensagem' => $cleanBotResponse
    ]);

    return response()->json([
        'conversation_id' => $conversation->id,
        'bot_response' => $cleanBotResponse,
    ]);
}

    public function sanitizeMessage(string $message): string {
    // Mantém letras, números, pontuação básica e espaços
    return preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', '', $message);
}

public function chat(Request $request, $conversationId = null)
{
    // Busca a conversa com as mensagens
    if ($conversationId) {
        $conversation = Conversation::with('messages')->findOrFail($conversationId);
    } else {
        $conversation = null;
    }

    // Carrega os bots disponíveis (caso queira permitir escolher)
    $bots = Bot::all();

    return view('admin.chat.index', [
        'conversation' => $conversation,
        'bots' => $bots,
    ]);
}



}