<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthControllerApi extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Usuario::where('email', $request->email)->first();
            $token = $user->createToken('authToken')->plainTextToken;
            $empresaId = $user->empresa?->id;
            $professorId = $user->professor?->id;
            return response()->json(['token' => $token, 'empresa_id' => $empresaId, 'professor_id' => $professorId], 200);
        } else {

            return response()->json(['error' => 'Invalid email/password'], 401);
        }
    }
}
