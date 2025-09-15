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


        $cleanUserMessage = $this->sanitizeMessage($request->mensagem);
        // Salva a mensagem do usuário
        $userMessage = Message::create([
            'from' => 'user',
            'to' => 'bot',
            'conversation_id' => $request->conversation_id,
            'role' => 'user',
            'body' => $cleanUserMessage 
        ]);

    Http::post('https://www.comunidadeppg.com.br:3000/enviarparaosass', [
                    'conversation_id' => $request->conversation_id,
                    'user_id' => $userId ?? 'guest',
                    'mensagem' => $cleanUserMessage,
                ]);

        
        $empresa = $conversation->empresa;
    
    
        if ($empresa && $empresa->telefone) {
            $userMessage->load('conversation'); // garante que tem a conversa
            app(\App\Services\TwilioService::class)
                ->enviarAlertaNovaMensagem($userMessage, $empresa);
        }
     
            
        // Gera resposta do bot
        // $botResponseText = $this->deepSeekService->getDeepSeekResponse(
        //     $conversation->bot,
        //     $request->mensagem,
        //     $conversation->empresa_id
        // );



       
        // $respostaboot = $this->sanitizeMessage($botResponseText);
        // Salva a resposta do bot
        $botMessage = Message::create([
            'from' => 'bot',
            'to' => 'user',
            'conversation_id' => $conversation->id,
              'role' => 'assistant',
            'body' =>  $request->mensagem
        ]);

        


        return response()->json([
            'conversation_id' => $conversation->id,
            'bot_response' =>  $request->mensagem,
        ]);
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


        $cleanUserMessage = $this->sanitizeMessage($request->mensagem);
        // Salva a mensagem do usuário
        $userMessage = Message::create([
            'from' => 'user',
            'to' => 'bot',
            'conversation_id' => $request->conversation_id,
            'role' => 'user',
            'body' => $cleanUserMessage 
        ]);

  

        
        $empresa = $conversation->empresa;
    
    
        if ($empresa && $empresa->telefone) {
            $userMessage->load('conversation'); // garante que tem a conversa
            app(\App\Services\TwilioService::class)
                ->enviarAlertaNovaMensagem($userMessage, $empresa);
        }
     
            
        // Gera resposta do bot
        $botResponseText = $this->deepSeekService->getDeepSeekResponse(
            $conversation->bot,
            $request->mensagem,
            $conversation->empresa_id
        );



        $respostaboot = $this->sanitizeMessage($botResponseText);
        // Salva a resposta do bot
        $botMessage = Message::create([
            'from' => 'bot',
            'to' => 'user',
            'conversation_id' => $conversation->id,
              'role' => 'assistant',
            'body' =>  $respostaboot
        ]);

        
          Http::post('https://www.comunidadeppg.com.br:3000/enviarparaosass', [
                    'conversation_id' => $request->conversation_id,
                    'user_id' => $userId ?? 'guest',
                    'mensagem' => $respostaboot,
                ]);
        

        return response()->json([
            'conversation_id' => $conversation->id,
            'bot_response' =>  $respostaboot,
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