<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMensagemRequest;
use App\Models\Alunos;
use App\Models\Bot;
use App\Models\BotLog;
use App\Models\BotService;
use App\Models\Conversation;
use App\Models\Empresa;
use App\Models\Message;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\TokenUsage;
use App\Models\Usuario;
use App\Services\DeepSeekService;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Services\FirebasePushService;
/**
 * Controlador de chat: mensagens, controle humano e integracoes externas.
 */
class ChatController extends Controller
{
    protected $deepSeekService, $twilioService, $firebasePushService;

    /**
     * Injeta os servicos usados para IA e notificacoes.
     */
    public function __construct(DeepSeekService $deepSeekService, TwilioService $twilioService, FirebasePushService $firebasePushService)
    {
        $this->deepSeekService = $deepSeekService;
        $this->twilioService = $twilioService;
        $this->firebasePushService = $firebasePushService;
    }


        public function mensagensDaConversa(Request $request)
        {
            $validated = $request->validate([
                'conversation_id' => 'required|integer|exists:conversations,id',
            ]);



            $conversationId = $validated['conversation_id'];
        
        
            $user = auth()->user();
         
            $conversation = Conversation::with([
                'messages' => function ($q) {
                    $q->orderBy('created_at', 'asc');
                },
                'user',
                'empresa',
            ])->findOrFail($conversationId);

            // 🔐 Empresa
            // if ($user->empresa && $conversation->empresa_id !== $user->empresa->id) {
            //     abort(403, 'Acesso negado.');
            // }  

            // // 🔐 Aluno
            // if ($user->aluno && $conversation->user_id !== $user->id) {
            //     abort(403, 'Acesso negado.');
            // }

            // 🔐 Professor
            if ($user->professor) {
                $alunoIds = $user->professor->alunos()
                    ->pluck('usuario_id')
                    ->toArray();

                if (!in_array($conversation->user_id, $alunoIds)) {
                    abort(403, 'Acesso negado.');
                }
            }

            return response()->json([
                'conversation_id' => $conversation->id,
                'aluno' => [
                    'id' => $conversation->user->id,
                    'nome' => $conversation->user->nome,
                ],
                'messages' => $conversation->messages->map(fn ($msg) => [
                    'id' => $msg->id,
                    'from' => $msg->from,
                    'to' => $msg->to,
                    'role' => $msg->role,
                    'body' => $msg->body,
                    'created_at' => $msg->created_at->toDateTimeString(),
                ]),
            ]);
        }



    /**
     * Lista conversas por empresa e usuario (aluno ou professor).
     */
    public function listByEmpresaAndUser(Request $request)
    {
         
        $validated = $request->validate([
            'empresa_id' => 'required|integer|exists:empresa,id',
            'user_id' => 'nullable|integer|exists:usuarios,id',
        ]);

        $userId = $validated['user_id'] ?? auth()->id();
       
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'user_id e obrigatorio quando nao autenticado.',
            ], 422);
        }
  
        $user = Usuario::with('professor.alunos')->findOrFail($userId);

        $query = Conversation::with([
            'user',
            'bot',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ])->where('empresa_id', $validated['empresa_id']);

        if ($user->tipo_usuario === 'professor' && $user->professor) {
            $alunoUserIds = $user->professor->alunos()->pluck('usuario_id')->filter()->all();
            $userIds = array_unique(array_merge($alunoUserIds, [$userId]));
            $query->whereIn('user_id', $userIds);
        } else {
            $query->where('user_id', $userId);
        }

        $conversas = $query->orderByDesc('updated_at')->get();

        return response()->json($conversas->map(function ($conversa) {
            $lastMessage = $conversa->messages->first();

            return [
                'id' => $conversa->id,
                'empresa_id' => $conversa->empresa_id,
                'user' => $conversa->user ? [
                    'id' => $conversa->user->id,
                    'name' => $conversa->user->nome ?? $conversa->user->name ?? null,
                    'email' => $conversa->user->email,
                ] : null,
                'bot' => $conversa->bot ? [
                    'id' => $conversa->bot->id,
                    'nome' => $conversa->bot->nome,
                ] : null,
                'human_controlled' => (bool) $conversa->human_controlled,
                'last_message' => $lastMessage ? [
                    'id' => $lastMessage->id,
                    'from' => $lastMessage->from,
                    'to' => $lastMessage->to,
                    'role' => $lastMessage->role,
                    'body' => $lastMessage->body,
                    'created_at' => $lastMessage->created_at->toDateTimeString(),
                ] : null,
                'created_at' => $conversa->created_at->toDateTimeString(),
                'updated_at' => $conversa->updated_at->toDateTimeString(),
            ];
        }));
    }

    /**
     * Lista contatos (nome/email) das conversas do aluno.
     */
    public function listarConversasAluno(Request $request)
    {
  
    $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:usuarios,id',
        ]);

        $userId = $validated['user_id'] ?? auth()->id();
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'user_id e obrigatorio quando nao autenticado.',
            ], 422);
        }

        $user = Usuario::with('aluno')->findOrFail($userId);
        if (!$user->aluno) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario informado nao e um aluno.',
            ], 403);
        }

        $query = Conversation::with(['empresa.user'])
            ->where('user_id', $userId)
            ->orderByDesc('updated_at');

        $conversas = $query->get()->unique('empresa_id')->values();

        return response()->json($conversas->map(function ($conversa) {
            $empresaUser = $conversa->empresa ? $conversa->empresa->user : null;

            return [
                'empresa_id' => $conversa->empresa_id,
                'conversation_id' => $conversa->id,
                'contato' => $empresaUser ? [
                    'id' => $empresaUser->id,
                    'nome' => $empresaUser->nome,
                    'email' => $empresaUser->email,
                ] : null,
            ];
        }));
    }

    /**
     * Lista conversas por professor (alunos vinculados) ou por empresa.
     */
    public function listByEmpresaOrProfessor(Request $request)
    {
        $validated = $request->validate([
            'empresa_id' => 'nullable|integer|exists:empresa,id',
            'professor_user_id' => 'nullable|integer|exists:usuarios,id',
        ]);

        $userId = $validated['professor_user_id'] ?? auth()->id();
        $empresaId = $validated['empresa_id'] ?? null;

        if (!$userId && !$empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'Informe professor_user_id ou empresa_id.',
            ], 422);
        }

        $user = $userId ? Usuario::with('professor.alunos', 'empresa')->findOrFail($userId) : null;

        if (!$empresaId && $user && $user->empresa) {
            $empresaId = $user->empresa->id;
        }

        $query = Conversation::with([
            'user',
            'bot',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ]);

        if ($empresaId) {
            $query->where('empresa_id', $empresaId);
        }

        if ($user && $user->tipo_usuario === 'professor' && $user->professor) {
            $alunoUserIds = $user->professor->alunos()->pluck('usuario_id')->filter()->all();
            $userIds = array_unique(array_merge($alunoUserIds, [$user->id]));
            $query->whereIn('user_id', $userIds);
        }

        $conversas = $query->orderByDesc('updated_at')->get();

        return response()->json($conversas->map(function ($conversa) {
            $lastMessage = $conversa->messages->first();

            return [
                'id' => $conversa->id,
                'empresa_id' => $conversa->empresa_id,
                'user' => $conversa->user ? [
                    'id' => $conversa->user->id,
                    'name' => $conversa->user->nome ?? $conversa->user->name ?? null,
                    'email' => $conversa->user->email,
                ] : null,
                'bot' => $conversa->bot ? [
                    'id' => $conversa->bot->id,
                    'nome' => $conversa->bot->nome,
                ] : null,
                'human_controlled' => (bool) $conversa->human_controlled,
                'last_message' => $lastMessage ? [
                    'id' => $lastMessage->id,
                    'from' => $lastMessage->from,
                    'to' => $lastMessage->to,
                    'role' => $lastMessage->role,
                    'body' => $lastMessage->body,
                    'created_at' => $lastMessage->created_at->toDateTimeString(),
                ] : null,
                'created_at' => $conversa->created_at->toDateTimeString(),
                'updated_at' => $conversa->updated_at->toDateTimeString(),
            ];
        }));
    }

    /**
     * Salva a mensagem do aluno para o professor e retorna a conversa.
     */
    public function alunoenviandomensagemparaoprofessor(Request $request)
    {
       
        $validated = $request->validate([
            'mensagem' => 'required|string',
            'professor_id' => 'required|integer|exists:usuarios,id',
            'conversation_id' => 'nullable|integer|exists:conversations,id',
            'empresa_id' => 'nullable|integer',
        ]);

        $alunoUserId = auth()->id() ;
  
        if (!$alunoUserId) {
            return response()->json([
                'success' => false,
                'message' => 'aluno_user_id e obrigatorio quando nao autenticado.',
            ], 422);
        }

            $alunoUser = auth()->user();

            if (!$alunoUser || !$alunoUser->aluno) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario autenticado nao e um aluno.',
                ], 403);
            }


        $professor = Professor::where('usuario_id', $validated['professor_id'])->first();
        if (!$professor) {
            return response()->json([
                'success' => false,
                'message' => 'Professor nao encontrado.',
            ], 404);
        }

        $empresaId = $validated['empresa_id'] ?? $professor->empresa_id ?? null;
        if (!$empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'empresa_id e obrigatorio para criar a conversa.',
            ], 422);
        }

        $conversationId = $validated['conversation_id'] ?? null;
        $conversation = $conversationId
            ? Conversation::findOrFail($conversationId)
            : Conversation::create([
                'empresa_id' => $empresaId,
                'bot_id' => null,
                'user_id' => $alunoUserId,
                'mensagem' => 'Inicio da conversa',
                'telefone' => $alunoUser->telefone ?? null,
                'human_controlled' => true,
            ]);

        $cleanMessage = $this->sanitizeMessage($validated['mensagem']);

        $message = Message::create([
            'from' => 'user',
            'to' => 'professor',
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'body' => $cleanMessage,
        ]);

        $this->enviarMensagemExterna($conversation->id, $cleanMessage, $alunoUserId);
        $this->enviarMensagemPasseioPayload([
            'conversation_id' => $conversation->id,
            'user_id' => $alunoUserId,
            'professor_id' => $validated['professor_id'],
            'mensagem' => $cleanMessage,
            'empresa_id' => $empresaId,
        ]);

       return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'message' => [
                'id' => $message->id,
                'from' => $message->from,
                'to' => $message->to,
                'role' => $message->role,
                'body' => $message->body,
                'created_at' => $message->created_at->toDateTimeString(),
            ]
        ]);

    }


    public function listarAlunos()
    {
        $alunos = Alunos::with('usuario')->get();

        return response()->json([
            'success' => true,
            'alunos' => $alunos
        ]);
    }



        public function listarProfessores(Request $request)
        {
            $empresaId = $request->get('empresa_id');

            $query = Professor::with('usuario');

            // Se quiser filtrar por empresa
            if ($empresaId) {
                $query->where('empresa_id', $empresaId);
            }

            $professores = $query->get()->map(function ($professor) {
                return [
                    'professor_id' => $professor->id,
                    'usuario_id'   => $professor->usuario->id ?? null,
                    'nome'         => $professor->usuario->nome ?? null,
                    'email'        => $professor->usuario->email ?? null,
                    'empresa_id'   => $professor->empresa_id,
                ];
            });

            return response()->json([
                'success' => true,
                'professores' => $professores
            ]);
        }


    /**
     * Salva a mensagem do professor para o aluno e retorna a conversa.
     */
    public function professorenviandomensagemparaaluno(Request $request)
    {
        
       
        $validated = $request->validate([
            'mensagem' => 'required|string',
            'aluno_user_id' => 'required|integer|exists:usuarios,id',
            'conversation_id' => 'nullable|integer|exists:conversations,id',
            'professor_user_id' => 'nullable|integer|exists:usuarios,id',
            'empresa_id' => 'nullable|integer',
        ]);


        $professorUserId = $validated['professor_user_id'];

        if (!$professorUserId) {
            return response()->json([
                'success' => false,
                'message' => 'professor_user_id e obrigatorio quando nao autenticado.',
            ], 422);   
        }

        $professorUser = Usuario::with('empresa')->find($professorUserId);
        $isProfessor = $professorUser && $professorUser->tipo_usuario === 'professor';
        $isEmpresa = $professorUser && $professorUser->empresa;
        if (!$professorUser || (!$isProfessor && !$isEmpresa)) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario informado nao e um professor ou empresa.',
            ], 403);
        }

        $professor = $isProfessor
            ? Professor::where('usuario_id', $professorUserId)->first()
            : null;
        if ($isProfessor && !$professor) {
            return response()->json([
                'success' => false,
                'message' => 'Professor nao encontrado.',
            ], 404);
        }

        $empresaId = $validated['empresa_id'] ?? ($professor->empresa_id ?? $professorUser->empresa->id ?? null);
        if (!$empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'empresa_id e obrigatorio para criar a conversa.',
            ], 422);
        }

        $conversationId = $validated['conversation_id'] ?? null;
        $conversation = $conversationId
            ? Conversation::findOrFail($conversationId)
            : Conversation::create([
                'empresa_id' => $empresaId,
                'bot_id' => null,
                'user_id' => $validated['aluno_user_id'],
                'mensagem' => 'Inicio da conversa',
                'telefone' => null,
                'human_controlled' => true,
            ]);

        $cleanMessage = $this->sanitizeMessage($validated['mensagem']);

        $message = Message::create([
            'from' => 'professor',
            'to' => 'aluno',
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'body' => $cleanMessage,
        ]);

        $this->firebasePushService->sendToUser(1 , 'Nova mensagem de aluno', 'TESTE AQUI');

        $this->enviarMensagemExterna($conversation->id, $cleanMessage, $professorUserId);
        $this->enviarMensagemPasseioPayload([
            'conversation_id' => $conversation->id,
            'user_id' => $validated['aluno_user_id'],
            'professor_id' => $professorUserId,
            'mensagem' => $cleanMessage,
            'empresa_id' => $empresaId,
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'message_id' => $message->id,
        ]);
    }

    /**
     * Alterna o controle humano de uma conversa e registra auditoria.
     */
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


    /**
     * Recebe mensagem do site, registra conversa e replica para endpoint externo.
     */
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
     * Entrada principal do chat: cria/recupera conversa, salva mensagem e gera resposta.
     *
     * @param \App\Http\Requests\StoreMensagemRequest $request
     */
    public function store(StoreMensagemRequest $request)
    {


        $bot = Bot::where('id', $request->bot_id)->first(); // garante que existe

        $userId = auth()->check() ? auth()->id() : null;


        // Busca ou cria a conversa
        $conversation = $request->conversation_id
            ? Conversation::find($request->conversation_id)
            : Conversation::createWithBot($bot, $request->phone, $userId, $request->empresa_id);

        // Sanitiza a mensagem do usuário
        $cleanUserMessage = $this->sanitizeMessage($request->mensagem);

        // Salva a mensagem do usuário
        $userMessage = Message::createUserMessage($conversation->id, $cleanUserMessage);

        $this->twilioService->enviarAlertaNovaMensagem($conversation->id, $userMessage, $request->empresa_id, $request->empresa_telefone);

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


    /**
     * Obtem a resposta do bot, envia para endpoint externo e salva no banco.
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


    /**
     * Envia mensagem para o endpoint externo (integracao).
     */


    protected function enviarMensagemExterna(int $conversationId, $mensagem, $userId = null): void
    {
        try {
            Http::timeout(3)->post('https://www.comunidadeppg.com.br:3000/chatmessage', [
                'conversation_id' => $conversationId,
                'user_id' => $userId ?? 'guest',
                'mensagem' => $mensagem,
            ]);

        } catch (\Throwable $e) {
            // Loga o erro mas NÃO quebra a aplicação
            Log::warning('Falha ao enviar mensagem externa', [
                'conversation_id' => $conversationId,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Encaminha mensagem para o endpoint externo de passeio.
     */
    public function enviarMensagemPasseio(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|integer|exists:conversations,id',
            'user_id' => 'nullable|integer|exists:usuarios,id',
            'professor_id' => 'required|integer|exists:usuarios,id',
            'mensagem' => 'required|string',
            'empresa_id' => 'required|integer|exists:empresa,id',
        ]);

        $userId = $validated['user_id'] ?? auth()->id();
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'user_id e obrigatorio quando nao autenticado.',
            ], 422);
        }

        $payload = [
            'conversation_id' => $validated['conversation_id'],
            'user_id' => $userId,
            'professor_id' => $validated['professor_id'],
            'mensagem' => $this->sanitizeMessage($validated['mensagem']),
            'empresa_id' => $validated['empresa_id'],
        ];

        $result = $this->enviarMensagemPasseioPayload($payload);
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao enviar mensagem externa.',
            ], 502);
        }

        return response()->json([
            'success' => true,
            'data' => $result['data'],
        ]);
    }

    protected function enviarMensagemPasseioPayload(array $payload): array
    {

         
        try {
            $response = Http::timeout(5)->post(
                'https://www.comunidadeppg.com.br:3000/enviarmensagempasseio',
                $payload
            );

            if (!$response->successful()) {
                Log::warning('Falha ao enviar mensagem de passeio', [
                    'status' => $response->status(),
                    'payload' => $payload,
                    'response' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'data' => null,
                ];
            }

            return [
                'success' => true,
                'data' => $response->json(),
            ];
        } catch (\Throwable $e) {
            Log::warning('Erro ao enviar mensagem de passeio', [
                'payload' => $payload,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'data' => null,
            ];
        }
    }



    /**
     * Gera imagem via fal.ai (Janus) e retorna URL publica.
     */
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


    /**
     * Mapeia dimensoes aceitas para o formato esperado pela fal.ai.
     */
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



    /**
     * Remove caracteres fora de letras, numeros, pontuacao basica e espacos.
     */
    public function sanitizeMessage(string $message): string
    {
        // Mantém letras, números, pontuação básica e espaços
        return preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', '', $message);
    }

    /**
     * Renderiza a tela do chat interno com conversa e bots disponiveis.
     */
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

    /**
     * Ativa ou desativa o controle humano via endpoint dedicado.
     */
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
