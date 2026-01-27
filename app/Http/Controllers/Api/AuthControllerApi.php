<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthControllerApi extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Nao autenticado'], 401);
        }

        return response()->json($user);
    }

    

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'error' => 'Invalid email/password'
        ], 401);
    }

    $user = Usuario::where('email', $request->email)->firstOrFail();

    // Cria token de autenticação
    $token = $user->createToken('authToken')->plainTextToken;

    // 🔔 VINCULA O FCM TOKEN (SE VIER)
    if ($request->filled('fcm_token')) {
        \App\Models\DeviceToken::updateOrCreate(
            [
                'fcm_token' => $request->fcm_token
            ],
            [
                'user_id'  => $user->id,
                'platform' => $request->input('platform', 'android')
            ]
        );
    }

    $empresaId   = $user->empresa?->id;
    $professorId = $user->professor?->id;
    $alunoId     = $user->aluno?->id;

    return response()->json([
        'token'        => $token,
        'empresa_id'   => $empresaId,
        'professor_id' => $professorId,
        'aluno_id'     => $alunoId,
          'user_id'     => $user->id
    ], 200);
}

}
