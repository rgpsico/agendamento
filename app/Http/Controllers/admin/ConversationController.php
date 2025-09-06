<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;

class ConversationController extends Controller
{
    public function index()
    {
        $conversas = Conversation::with('bot', 'user')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.conversations.index', compact('conversas'));
    }

    public function show($id)
    {
        $conversa = Conversation::with('bot', 'user')
            ->findOrFail($id);

        return view('admin.conversations.show', compact('conversa'));
    }
}
