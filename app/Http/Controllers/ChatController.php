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
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    protected $deepSeekService, $twilioService;

    public function __construct(DeepSeekService $deepSeekService, TwilioService $twilioService)
    {
        $this->deepSeekService = $deepSeekService;
        $this->twilioService = $twilioService;
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


        $this->twilioService->enviarAlertaNovaMensagem($conversation->id, $userMessage, $request->empresa_id, $request->empresa_telefone);


        return response()->json([
            'conversation_id' => $conversation->id,
            'bot_response' => $respostaBot,
        ]);
    }


    /**
     * Obtem a resposta do bot e salva no banco.
     */
    private function getBotResponse($mensagem, $conversation, $userId)
    {
        $botResponseText = $this->deepSeekService->getDeepSeekResponseWithPrompt(
            $conversation->bot,
            $mensagem,
            $conversation,
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


    protected function enviarMensagemExterna(int $conversationId, $mensagem, $userId = null)
    {
        Http::post('https://www.comunidadeppg.com.br:3000/chatmessage', [
            'conversation_id' => $conversationId,
            'user_id' => $userId ?? 'guest',
            'mensagem' => $mensagem,
        ]);
    }


    public function generateImage(Request $request)
    {
        // Validação dos parâmetros
        $request->validate([
            'prompt' => 'required|string|max:500',
            'width' => 'sometimes|integer|in:256,512,1024', // Dimensões compatíveis
            'height' => 'sometimes|integer|in:256,512,1024',
        ]);

        $prompt = $request->input('prompt');
        $width = $request->input('width', 512);
        $height = $request->input('height', 512);

        // Mapear dimensões para image_size da fal.ai
        $imageSize = $this->mapToFalAiImageSize($width, $height);
        if (!$imageSize) {
            return response()->json([
                'success' => false,
                'erro' => 'Dimensões inválidas. Use combinações como 512x512, 1024x768, ou 768x1024.',
            ], 400);
        }

        // Verifica se a chave existe
        $apiKey = env('FAL_AI_API_KEY');
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'erro' => 'Chave API da fal.ai não configurada em .env (FAL_AI_API_KEY)',
            ], 500);
        }

        try {
            // Chama a API do fal.ai (Janus-Pro)
            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => 'Key ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://fal.run/fal-ai/janus', [
                    'prompt' => $prompt,
                    'image_size' => $imageSize, // Ex: "square", "landscape_4_3"
                    'num_inference_steps' => 28,
                    'guidance_scale' => 5.0,
                    'seed' => rand(0, 1000000),
                ]);

            // Verifica status
            if (!$response->successful()) {
                $errorBody = $response->body();
                Log::error('Erro na API fal.ai (Janus)', [
                    'status' => $response->status(),
                    'error' => $errorBody,
                    'prompt' => $prompt,
                    'image_size' => $imageSize,
                ]);
                throw new \Exception("Falha na geração: {$errorBody}");
            }

            $data = $response->json();

            // Verifica resposta
            if (!isset($data['images']) || empty($data['images'])) {
                throw new \Exception('Resposta inválida: Nenhuma imagem gerada');
            }

            $imagemData = $data['images'][0];

            // Se base64, salva localmente; senão, usa URL
            if (isset($imagemData['base64']) && !empty($imagemData['base64'])) {
                $nomeArquivo = 'deepseek_janus_' . time() . '.png';
                $imagemBinaria = base64_decode($imagemData['base64']);
                if ($imagemBinaria === false) {
                    throw new \Exception('Erro ao decodificar base64');
                }
                Storage::disk('public')->put($nomeArquivo, $imagemBinaria);
                $imagemUrl = asset("storage/{$nomeArquivo}");
            } else {
                $imagemUrl = $imagemData['url'] ?? null;
                if (empty($imagemUrl)) {
                    throw new \Exception('URL da imagem não encontrada na resposta');
                }
            }

            return response()->json([
                'success' => true,
                'imagem_url' => $imagemUrl,
                'request_id' => $data['request_id'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao gerar imagem com fal.ai', [
                'message' => $e->getMessage(),
                'prompt' => $prompt,
                'image_size' => $imageSize,
            ]);

            return response()->json([
                'success' => false,
                'erro' => $e->getMessage(),
            ], 500);
        }
    }


    private function mapToFalAiImageSize($width, $height)
    {
        $sizes = [
            '512x512' => 'square',
            '1024x1024' => 'square_hd',
            '768x1024' => 'portrait_4_3',
            '1024x768' => 'landscape_4_3',
            '576x1024' => 'portrait_16_9',
            '1024x576' => 'landscape_16_9',
        ];

        $key = "{$width}x{$height}";
        return $sizes[$key] ?? null;
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
