<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMensagemRequest;
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


    public function updateControl(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'human_control' => 'required|boolean'
        ]);

        try {
            // Buscar a conversa
            $conversation = Conversation::findOrFail($request->conversation_id);

            // Atualizar o campo de controle
            $conversation->update([
                'human_controlled' => $request->human_control,
                'controlled_by' => $request->human_control ? auth()->id() : null,
                'control_changed_at' => now()
            ]);

            // Log da mudança para auditoria
            Log::info('Controle de conversa alterado', [
                'conversation_id' => $conversation->id,
                'human_controlled' => $request->human_control,
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);

            // Opcional: Enviar notificação via Socket.IO para outros usuários
            // $this->notifyControlChange($conversation->id, $request->human_control);

            return response()->json([
                'success' => true,
                'message' => $request->human_control ? 'Controle transferido para humano' : 'Controle transferido para bot',
                'human_controlled' => $request->human_control,
                'controlled_by' => $request->human_control ? auth()->user()->name : 'Bot'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar controle de conversa: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
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
                ->enviarAlertaNovaMensagem($conversation->id, $userMessage, $empresa);
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

    //Metodo enviar do site para o bate papo interno

    /**
     * @param \App\Http\Requests\StoreMensagemRequest $request
     */
    public function store(StoreMensagemRequest $request)
    {
        $userId = auth()->check() ? auth()->id() : null;

        // Busca ou cria a conversa
        $conversation = $request->conversation_id
            ? Conversation::find($request->conversation_id)
            : Conversation::createWithBot($request->phone, $userId);

        // Sanitiza a mensagem do usuário
        $cleanUserMessage = $this->sanitizeMessage($request->mensagem);

        // Salva a mensagem do usuário
        $userMessage = Message::createUserMessage($conversation->id, $cleanUserMessage);

        $respostaBot = null;

        // Chama o DeepSeek apenas se não estiver sob controle humano
        if (!$conversation->human_controlled) {
            $respostaBot = $this->getBotResponse($request->mensagem, $conversation, $userId);
        }

        // Sempre envia a mensagem do usuário para o endpoint externo
        $this->enviarMensagemExterna($conversation->id, $request->mensagem, $userId);


        return response()->json([
            'conversation_id' => $conversation->id,
            'bot_response' => $respostaBot,
        ]);
    }


    protected function enviarMensagemExterna(int $conversationId, $mensagem, $userId = null)
    {
        Http::post('https://www.comunidadeppg.com.br:3000/chatmessage', [
            'conversation_id' => $conversationId,
            'user_id' => $userId ?? 'guest',
            'mensagem' => $mensagem,
        ]);
    }


    /**
     * Obtem a resposta do bot e salva no banco.
     */
    private function getBotResponse($mensagem, $conversation, $userId)
    {
        $botResponseText = $this->deepSeekService->getDeepSeekResponse(
            $conversation->bot,
            $mensagem,
            $conversation->empresa_id
        );

        $respostaBot = $this->sanitizeMessage($botResponseText);

        if ($respostaBot) {
            // Envia para endpoint externo
            Http::post('https://www.comunidadeppg.com.br:3000/enviarparaosass', [
                'conversation_id' => $conversation->id,
                'user_id' => $userId ?? 'guest',
                'mensagem' => $respostaBot,
            ]);



            // Salva a resposta do bot
            Message::createBotMessage($conversation->id, $respostaBot);
        }

        return $respostaBot;
    }




    public function sanitizeMessage(string $message): string
    {
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

    public function toggleHumanControl(Request $request, $id)
    {
        $request->validate([
            'human_controlled' => 'boolean',
        ]);

        $conversation = Conversation::findOrFail($id);



        $conversation->update([
            'human_controlled' => $request->human_controlled,
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'human_controlled' => $conversation->human_controlled,
            'message' => $conversation->human_controlled
                ? 'Controle humano ativado'
                : 'Controle humano desativado',
        ]);
    }
}
