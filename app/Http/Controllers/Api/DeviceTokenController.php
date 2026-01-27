<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceToken;

class DeviceTokenController extends Controller
{
    /**
     * Salva ou atualiza o token FCM do dispositivo
     */
    public function store(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'platform'  => 'required|string|max:20',
        ]);

        DeviceToken::updateOrCreate(
            [
                'fcm_token' => $request->fcm_token,
            ],
            [
                'user_id'  => auth()->id(), // null se não estiver logado
                'platform' => $request->platform,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Token salvo com sucesso',
        ]);
    }

    /**
     * (Opcional) Remover token — útil no logout
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        DeviceToken::where('fcm_token', $request->fcm_token)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token removido',
        ]);
    }
}
