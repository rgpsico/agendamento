<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

/**
 * Controlador do chat do professor: lista mensagens da conversa.
 */
class ChatProfessorController extends Controller
{
    /**
     * Lista mensagens de uma conversa pelo professor usando empresa e usuario.
     */
    public function listarMensagensByIdConversa(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|integer|exists:conversations,id',
            'empresa_id' => 'required|integer|exists:empresa,id',
        ]);

        $conversation = Conversation::with([
            'messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            },
        ])->findOrFail($validated['conversation_id']);

        return response()->json([
            'conversation_id' => $conversation->id,
            'empresa_id' => $conversation->empresa_id,
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

    /**
     * Envia mensagem do professor para o aluno em uma conversa.
     */
    public function enviarmensagemaoaluno(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:usuarios,id',
            'empresa_id' => 'required|integer|exists:empresa,id',
            'conversation_id' => 'required|integer|exists:conversations,id',
            'mensagem' => 'required|string',
        ]);

        $conversation = Conversation::where('id', $validated['conversation_id'])
            ->where('empresa_id', $validated['empresa_id'])
            ->where('user_id', $validated['user_id'])
            ->firstOrFail();

        $message = Message::create([
            'from' => 'professor',
            'to' => 'aluno',
            'conversation_id' => $conversation->id,
            'role' => 'assistant',
            'body' => trim($validated['mensagem']),
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
        ]);
    }

    /**
     * Verifica se aluno e empresa possuem conversa aberta e retorna mensagens.
     */
    public function verificarConversaAlunoEmpresa(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:usuarios,id',
            'empresa_id' => 'required|integer|exists:empresa,id',
        ]);

        $conversation = Conversation::where('empresa_id', $validated['empresa_id'])
            ->where('user_id', $validated['user_id'])
            ->latest('created_at')
            ->first();

        if (!$conversation) {
            return response()->noContent();
        }

        $conversation->load([
            'messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            },
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'empresa_id' => $conversation->empresa_id,
            'user_id' => $conversation->user_id,
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
