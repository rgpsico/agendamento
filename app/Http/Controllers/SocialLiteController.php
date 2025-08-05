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
            // Recupera dados do Google
            $googleUser = Socialite::driver('google')
                ->scopes(['openid', 'email', 'profile'])
                ->stateless()
                ->redirectUrl(config('services.google.redirect'))
                ->user();


            dd($googleUser);
            // Log para depuração
            \Log::info('Google User Data: ' . json_encode($googleUser));

            // Busca ou cria usuário
            $user = Usuario::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = Usuario::create([
                    'nome' => $googleUser->name ?? $googleUser->email,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'tipo_usuario' => 'Aluno',
                    'remember_token' => Str::random(60),
                    'password' => bcrypt(Str::random(16)),
                ]);

                Alunos::create([
                    'usuario_id' => $user->id
                ]);
            }

            // Salva os tokens do Google
            $user->google_access_token = $googleUser->token;
            $user->google_refresh_token = $googleUser->refreshToken ?? null;
            $user->google_token_expire = now()->addSeconds($googleUser->expiresIn);
            $user->save();

            // Testa o endpoint userinfo
            $client = new \GuzzleHttp\Client();
            $response = $client->get('https://www.googleapis.com/oauth2/v3/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $googleUser->token,
                ],
            ]);
            \Log::info('Userinfo response: ' . $response->getBody());

            // Faz login no usuário
            Auth::login($user, true);

            // Redireciona com base no tipo de usuário
            $user->load('aluno');
            if (strtolower($user->tipo_usuario) === 'professor') {
                return redirect()->route('admin.dashboard', ['id' => $user->id]);
            } elseif (strtolower($user->tipo_usuario) === 'aluno') {
                return redirect()->route('alunos.fotos', ['id' => $user->aluno->id]);
            }

            return redirect('/')->with('error', 'Tipo de usuário não reconhecido.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            \Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Erro ao tentar autenticar com o Google: ' . $e->getMessage());
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
