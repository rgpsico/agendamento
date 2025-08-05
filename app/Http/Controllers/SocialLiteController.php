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
        dd("aaaa");
        try {
            // Recupera dados do Google + tokens
            $googleUser = Socialite::driver('google')->stateless()->user();
            dd($googleUser->token); // Inspect the access token

            $user = Usuario::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = Usuario::create([
                    'nome' => $googleUser->name ?? $googleUser->email,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'tipo_usuario' => 'Aluno',
                    'remember_token' => Str::random(60),
                    'password' => bcrypt(124)
                ]);

                Alunos::create([
                    'usuario_id' => $user->id
                ]);
            }

            // Aqui está o segredo: tokens do Google
            $accessToken = $googleUser->token;
            $refreshToken = $googleUser->refreshToken ?? null;
            $expiresIn = $googleUser->expiresIn;

            // 💾 Salve esses tokens na sua tabela (ex: google_tokens, ou dentro da tabela `usuarios`)
            $user->google_access_token = $accessToken;
            $user->google_refresh_token = $refreshToken;
            $user->google_token_expire = now()->addSeconds($expiresIn);
            $user->save();

            // Login no sistema
            Auth::login($user, true);

            // Redireciona com base no tipo de usuário
            $user->load('aluno');
            if (strtolower($user->tipo_usuario) === 'professor') {
                return redirect()->route('admin.dashboard', ['id' => $user->id]);
            } elseif (strtolower($user->tipo_usuario) === 'aluno') {
                return redirect()->route('alunos.fotos', ['id' => $user->aluno->id]);
            } else {
                return redirect('/')->with('error', 'Tipo de usuário não reconhecido.');
            }
        } catch (\Exception $e) {
            dd($e);
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
