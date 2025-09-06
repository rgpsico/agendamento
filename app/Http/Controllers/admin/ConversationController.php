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



}
