<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Criar notificação para um usuário
    public function createForUser($userId, $title, $message, $type = 'info')
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    // Criar notificação para uma empresa
    public function createForEmpresa($empresaId, $title, $message, $type = 'info')
    {
        Notification::create([
            'empresa_id' => $empresaId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    // Listar notificações do usuário logado
    public function getUserNotifications()
    {
        $userId = Auth::id();
        return response()->json(Notification::where('user_id', $userId)->where('status', 'unread')->get());
    }

    // Listar notificações da empresa logada
    public function getEmpresaNotifications()
    {
        $empresaId = Auth::user()->empresa->id ?? null;
        if (!$empresaId) {
            return response()->json(['error' => 'Empresa não encontrada'], 404);
        }
        return response()->json(Notification::where('empresa_id', $empresaId)->where('status', 'unread')->get());
    }

    // Marcar uma notificação como lida
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->status = 'read';
            $notification->save();
            return response()->json(['success' => 'Notificação marcada como lida']);
        }
        return response()->json(['error' => 'Notificação não encontrada'], 404);
    }
}
