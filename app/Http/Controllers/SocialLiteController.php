<?php

namespace App\Http\Controllers;

use App\Models\Alunos;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLiteController extends Controller
{


    public function alunoRedirectToGoogle()
    {

        return $user =  Socialite::driver('google')->redirect();
    }

    public function alunoGoogleCallback(Request $request)
    {
        try {
            // Obtém os dados do usuário autenticado via Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Debug opcional para verificar os dados recebidos
            dd([
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'token' => $googleUser->token,
                'refreshToken' => $googleUser->refreshToken,
                'expiresIn' => $googleUser->expiresIn,
            ]);

            // Busca usuário pelo e-mail
            $user = Usuario::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = Usuario::create([
                    'nome' => $googleUser->getName() ?? $googleUser->getEmail(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'tipo_usuario' => 'Aluno',
                    'remember_token' => Str::random(60),
                    'password' => bcrypt(124) // Ideal seria gerar senha aleatória e enviar para e-mail depois
                ]);

                Alunos::create([
                    'usuario_id' => $user->id
                ]);
            }

            // Salva os tokens (acesso, refresh e expiração)
            $user->google_access_token = $googleUser->token;
            $user->google_refresh_token = $googleUser->refreshToken ?? null;
            $user->google_token_expire = now()->addSeconds($googleUser->expiresIn);
            $user->save();

            // Autentica o usuário
            Auth::login($user, true);

            // Redirecionamento baseado no tipo de usuário
            $user->load('aluno');

            return match (strtolower($user->tipo_usuario)) {
                'professor' => redirect()->route('admin.dashboard', ['id' => $user->id]),
                'aluno'     => redirect()->route('alunos.fotos', ['id' => $user->aluno->id]),
                default     => redirect('/')->with('error', 'Tipo de usuário não reconhecido.')
            };
        } catch (\Exception $e) {
            // Exibe erro para debugging
            dd($e->getMessage(), $e);
            return redirect('/')->with('error', 'Erro ao tentar autenticar com o Google.');
        }
    }




    public function professorRedirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function professorGoogleCallback(Request $request)
    {

        $googleUser = Socialite::driver('google')->user();
        $user = Usuario::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = Usuario::create([
                'nome' => $googleUser->name ?? $googleUser->email,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'tipo_usuario' => 'Professor', // Agora setado como professor
                'remember_token' => Str::random(60),
                'password' => bcrypt(124)
            ]);

            Professor::create([
                'usuario_id' => $user->id
                // Adicione mais campos padrão caso necessário
            ]);
        }

        Auth::login($user, true);

        if (!Auth::check()) {
            return redirect('/')->with('error', 'Login falhou após autenticação do Google.');
        }

        // Carrega relacionamento (professor)
        $user->load('professor');

        // Redireciona com base no tipo de usuário
        if (strtolower($user->tipo_usuario) === 'professor') {
            return redirect()->route('admin.dashboard', ['id' => $user->professor->id]);
        } elseif (strtolower($user->tipo_usuario) === 'aluno') {
            return redirect()->route('alunos.fotos', ['id' => $user->aluno->id]);
        } else {
            return redirect('/')->with('error', 'Tipo de usuário não reconhecido.');
        }
    }
}
