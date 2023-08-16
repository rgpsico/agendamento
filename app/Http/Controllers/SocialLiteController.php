<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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

            dd($googleUser);


            $user = Usuario::where('email', $googleUser->email)->first();

            if (!$user) {
                // Crie um novo usuário ou modifique conforme suas necessidades
                $user = Usuario::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(124), // apenas um password aleatório, já que o login será pelo Google
                ]);
            }

            // Autentique o usuário
            Auth::login($user, true);

            return redirect('/');  // ou onde você deseja redirecionar após o login

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Houve um problema ao tentar autenticar com o Google.' . $e);
        }
    }
}
