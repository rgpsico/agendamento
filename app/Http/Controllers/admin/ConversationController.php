<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;

class ConversationController extends Controller
{
    public function index()
    {
        $conversas = Conversation::with('bot', 'user')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.conversations.index', compact('conversas'));
    }

   public function show($conversation_id)
    {
        // Carrega a conversa com mensagens e usuário/bot relacionados
        $conversa = Conversation::with('messages', 'user', 'bot')
            ->findOrFail($conversation_id);

        return view('admin.conversations.show', compact('conversa'));
    }


    public function showapi($conversation_id)
{
    // Carrega a conversa com mensagens, usuário e bot relacionados
    $conversa = Conversation::with('messages', 'user', 'bot')
        ->findOrFail($conversation_id);

    // Retorna em JSON
    return response()->json([
        'id' => $conversa->id,
        'empresa_id' => $conversa->empresa_id,
        'user' => $conversa->user ? [
            'id' => $conversa->user->id,
            'name' => $conversa->user->name,
            'email' => $conversa->user->email,
        ] : null,
        'bot' => $conversa->bot ? [
            'id' => $conversa->bot->id,
            'nome' => $conversa->bot->nome,
        ] : null,
        'messages' => $conversa->messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'from' => $msg->from,
                'to' => $msg->to,
                'role' => $msg->role,
                'body' => $msg->body,
                'created_at' => $msg->created_at->toDateTimeString(),
            ];
        }),
        'created_at' => $conversa->created_at->toDateTimeString(),
        'updated_at' => $conversa->updated_at->toDateTimeString(),
    ]);
}




}
