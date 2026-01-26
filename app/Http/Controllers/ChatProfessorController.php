<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
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
}
