<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Usuario;
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
            'empresa_id' => 'required|integer|exists:empresa,id'
        ]);

      
        $professorUser = Usuario::with('professor.alunos')->findOrFail($validated['empresa_id']);
        if (!$professorUser->professor) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario informado nao e um professor.',
            ], 403);
        }

        if ((int) $professorUser->professor->empresa_id !== (int) $validated['empresa_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Professor nao pertence a empresa informada.',
            ], 403);
        }

        $authUser = auth()->user();
        if ($authUser && $authUser->tipo_usuario === 'professor' && (int) $authUser->id !== (int) $professorUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado.',
            ], 403);
        }

        $conversation = Conversation::with([
            'messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            },
            'user',
        ])->where('id', $validated['conversation_id'])
            ->where('empresa_id', $validated['empresa_id'])
            ->firstOrFail();

        $alunoUserIds = $professorUser->professor->alunos()
            ->pluck('usuario_id')
            ->filter()
            ->toArray();

        if (!in_array($conversation->user_id, $alunoUserIds, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado.',
            ], 403);
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'empresa_id' => $conversation->empresa_id,
            'aluno' => $conversation->user ? [
                'id' => $conversation->user->id,
                'nome' => $conversation->user->nome,
                'email' => $conversation->user->email,
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
