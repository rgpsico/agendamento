<?php

namespace App\Http\Controllers;

use App\Models\Alunos;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SocialLiteController extends Controller
{
    public function alunoRedirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile'])
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    protected function getGoogleUserData(Request $request)
    {
        $googleUser = Socialite::driver('google')
            ->scopes(['openid', 'email', 'profile'])
            ->stateless()
            ->redirectUrl(config('services.google.redirect'))
            ->user();
        dd($googleUser);
        Log::info('Google User Data: ' . json_encode($googleUser));
        return $googleUser;
    }

    protected function saveUserData($googleUser)
    {
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

        return $user;
    }

    protected function testUserInfoEndpoint($googleUser)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://www.googleapis.com/oauth2/v3/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $googleUser->token,
            ],
        ]);
        Log::info('Userinfo response: ' . $response->getBody());
        return $response->json();
    }

    protected function loginAndRedirect($user)
    {
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
    }

    public function alunoGoogleCallback(Request $request)
    {
        try {
            // Etapa 1: Obtém os dados do Google
            $googleUser = $this->getGoogleUserData($request);

            // Etapa 2: Salva os dados do usuário
            $user = $this->saveUserData($googleUser);

            // Etapa 3: Testa o endpoint userinfo (opcional, para depuração)
            $this->testUserInfoEndpoint($googleUser);

            // Etapa 4: Faz login e redireciona
            return $this->loginAndRedirect($user);
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Erro ao tentar autenticar com o Google: ' . $e->getMessage());
        }
    }

    // Métodos para professores (mantidos como estavam)
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
                'tipo_usuario' => 'Professor',
                'remember_token' => Str::random(60),
                'password' => bcrypt(124)
            ]);

            Professor::create([
                'usuario_id' => $user->id
            ]);
        }

        Auth::login($user, true);

        if (!Auth::check()) {
            return redirect('/')->with('error', 'Login falhou após autenticação do Google.');
        }

        $user->load('professor');

        if (strtolower($user->tipo_usuario) === 'professor') {
            return redirect()->route('admin.dashboard', ['id' => $user->professor->id]);
        } elseif (strtolower($user->tipo_usuario) === 'aluno') {
            return redirect()->route('alunos.fotos', ['id' => $user->aluno->id]);
        } else {
            return redirect('/')->with('error', 'Tipo de usuário não reconhecido.');
        }
    }
}
