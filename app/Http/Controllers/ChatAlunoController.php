<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;

/**
 * Controlador do chat do aluno: lista conversas e interacoes com empresa.
 */
class ChatAlunoController extends Controller
{
    /**
     * Lista todas as conversas do aluno.
     */
    public function listarConversasDoAluno(Request $request)
    {

   
        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:usuarios,id',
            'aluno_user_id' => 'nullable|integer|exists:usuarios,id',
        ]);

        $userId = $validated['user_id']
            ?? $validated['aluno_user_id']
            ?? auth()->id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'user_id e obrigatorio quando nao autenticado.',
            ], 422);
        }

        $alunoUser = Usuario::with('aluno')->findOrFail($userId);
        if (!$alunoUser->aluno) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario informado nao e um aluno.',
            ], 403);
        }

        $authUser = auth()->user();
        if ($authUser && $authUser->tipo_usuario === 'aluno' && (int) $authUser->id !== (int) $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado.',
            ], 403);
        }

        $conversas = Conversation::with([
            'empresa.user',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ])
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->get()
            ->unique('empresa_id')
            ->values();

        return response()->json($conversas->map(function ($conversa) {
            $lastMessage = $conversa->messages->first();
            $empresaUser = $conversa->empresa ? $conversa->empresa->user : null;

            return [
                'conversation_id' => $conversa->id,
                'empresa_id' => $conversa->empresa_id,
                'contato' => $empresaUser ? [
                    'id' => $empresaUser->id,
                    'nome' => $empresaUser->nome,
                    'email' => $empresaUser->email,
                ] : null,
                'last_message' => $lastMessage ? [
                    'id' => $lastMessage->id,
                    'from' => $lastMessage->from,
                    'to' => $lastMessage->to,
                    'role' => $lastMessage->role,
                    'body' => $lastMessage->body,
                    'created_at' => $lastMessage->created_at->toDateTimeString(),
                ] : null,
                'updated_at' => $conversa->updated_at->toDateTimeString(),
            ];
        }));
    }

    /**
     * Lista todas as conversas e mensagens do aluno com uma empresa.
     */
    public function listarInteracoesAlunoEmpresa(Request $request)
    { 

        $validated = $request->validate([
            'empresa_id' => 'required|integer|exists:empresa,id',
            'user_id' => 'nullable|integer|exists:usuarios,id',
        ]);

        $userId = $validated['user_id']
            ?? $validated['aluno_user_id']
            ?? auth()->id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'user_id e obrigatorio quando nao autenticado.',
            ], 422);
        }

        $alunoUser = Usuario::with('aluno')->findOrFail($userId);
        if (!$alunoUser->aluno) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario informado nao e um aluno.',
            ], 403);
        }

        $authUser = auth()->user();
        if ($authUser && $authUser->tipo_usuario === 'aluno' && (int) $authUser->id !== (int) $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado.',
            ], 403);
        }

        $conversas = Conversation::with([
            'empresa.user',
            'messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            },
        ])
            ->where('empresa_id', $validated['empresa_id'])
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->get();

        return response()->json($conversas->map(function ($conversa) {
            $empresaUser = $conversa->empresa ? $conversa->empresa->user : null;

            return [
                'conversation_id' => $conversa->id,
                'empresa_id' => $conversa->empresa_id,
                'contato' => $empresaUser ? [
                    'id' => $empresaUser->id,
                    'nome' => $empresaUser->nome,
                    'email' => $empresaUser->email,
                ] : null,
                'messages' => $conversa->messages->map(function ($msg) {
                    return [
                        'id' => $msg->id,
                        'from' => $msg->from,
                        'to' => $msg->to,
                        'role' => $msg->role,
                        'body' => $msg->body,
                        'created_at' => $msg->created_at->toDateTimeString(),
                    ];
                }),
                'updated_at' => $conversa->updated_at->toDateTimeString(),
            ];
        }));
    }

    /**
     * Envia mensagem do aluno para o professor da empresa.
     */
    public function enviarMensagemParaProfessor(Request $request)
    {
       
      
        $validated = $request->validate([
            'mensagem' => 'required|string',
            'empresa_id' => 'required|integer|exists:empresa,id',
            'user_id' => 'required|integer|exists:usuarios,id',
            'conversation_id' => 'nullable|integer',
        ]);

       
       

        $alunoUser = Usuario::with('aluno')->find($validated['user_id']);
        if (!$alunoUser->aluno) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario informado nao e um aluno.',
            ], 403);
        }

        $authUser = auth()->user();
        if ($authUser && $authUser->tipo_usuario === 'aluno' && (int) $authUser->id !== (int) $alunoUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado.',
            ], 403);
        }

        $professor = null;
        if (!empty($validated['professor_id'])) {
            $professor = Professor::where('usuario_id', $validated['professor_id'])->first();
        }

        if (!$professor) {
            $professor = Professor::where('empresa_id', $validated['empresa_id'])->first();
        }

        if (!$professor) {
            return response()->json([
                'success' => false,
                'message' => 'Professor nao encontrado para a empresa informada.',
            ], 404);
        }

        $conversation = !empty($validated['conversation_id'])
            ? Conversation::findOrFail($validated['conversation_id'])
            : Conversation::create([
                'empresa_id' => $validated['empresa_id'],
                'bot_id' => null,
                'user_id' => $alunoUser->id,
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
            ],
            'professor_id' => $professor->usuario_id,
        ]);
    }

    /**
     * Remove caracteres fora de letras, numeros, pontuacao basica e espacos.
     */
    private function sanitizeMessage(string $message): string
    {
        return preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}]/u', '', $message);
    }

    /**
     * Lista mensagens de uma conversa pelo aluno.
     */
    public function listarMensagensByIdConversa(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|integer|exists:conversations,id',
            'user_id' => 'required|integer|exists:usuarios,id',
        ]);

        $alunoUser = Usuario::with('aluno')->find($validated['user_id']);
        if (!$alunoUser->aluno) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario informado nao e um aluno.',
            ], 403);
        }

        $authUser = auth()->user();
        if ($authUser && $authUser->tipo_usuario === 'aluno' && (int) $authUser->id !== (int) $alunoUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado.',
            ], 403);
        }

        $conversation = Conversation::with([
            'messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            },
            'empresa.user',
        ])->where('id', $validated['conversation_id'])
            ->where('user_id', $validated['user_id'])
            ->firstOrFail();

        $empresaUser = $conversation->empresa ? $conversation->empresa->user : null;

        return response()->json([
            'conversation_id' => $conversation->id,
            'empresa_id' => $conversation->empresa_id,
            'contato' => $empresaUser ? [
                'id' => $empresaUser->id,
                'nome' => $empresaUser->nome,
                'email' => $empresaUser->email,
            ] : null,
            'messages' => $conversation->messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'from' => $msg->from,
                    'to' => $msg->to,
                    'role' => $msg->role,
                    'body' => $msg->body,
                    'created_at' => $msg->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }
}
