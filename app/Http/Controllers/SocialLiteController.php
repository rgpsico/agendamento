<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLiteController extends Controller
{


    public function redirectToGoogle()
    {

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {


        try {
            $googleUser = Socialite::driver('google')->user();



            $user = Usuario::where('email', $googleUser->email)->first();

            if (!$user) {
                // Crie um novo usuário ou modifique conforme suas necessidades
                $user = Usuario::create([
                    'nome' => $googleUser->name, // Aqui você deve provavelmente usar ->name ao invés de ->email
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'tipo_usuario' => 'aluno',
                    //  'remember_token' => Str::random(60), // Gerar um token aleatório
                    'password' => bcrypt(124) // Isso deve ser atualizado para algo mais seguro mais tarde
                ]);
            }


            Auth::login($user, true);

            return redirect()->route("admin.dashboard");  // ou onde você deseja redirecionar após o login

        } catch (\Exception $e) {
            dd($e);
            \Log::error('Erro com autenticação do Google: ' . $e->getMessage());

            dd($e);
            return redirect('/')->with('error', 'Erro ao tentar autenticar com o Google.');
        }
    }
}
