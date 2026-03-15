<?php

namespace App\Http\Controllers;

use App\Models\Alunos;
use App\Models\GoogleAdsToken;
use App\Models\Professor;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLiteController extends Controller
{

    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->clientId = env('GOOGLE_CLIENT_ID');
        $this->clientSecret = env('GOOGLE_CLIENT_SECRET');
        $this->redirectUri = env('GOOGLE_REDIRECT_URL');
    }

    public function alunoRedirectToGoogle()
    {
        return $user =  Socialite::driver('google')->redirect();
    }

    public function alunoGoogleCallback(Request $request)
    {

        $code = $request->get('code');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
        ]);

        $data = $response->json();

        $expiresAt = Carbon::now()->addSeconds($data['expires_in']);
        $empresaId = 17;
        // Atualiza ou cria o registro do token
        GoogleAdsToken::updateOrCreate(
            ['empresa_id' => $empresaId], // <- substitua $empresaId conforme o contexto
            [
                'google_account_id'        => null, // ou preencha se tiver
                'access_token'             => $data['access_token'],
                'refresh_token'            => $data['refresh_token'] ?? null,
                'access_token_expires_at'  => $expiresAt,
            ]
        );

        try {



            $googleUser = Socialite::driver('google')->user();
            $user = Usuario::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = Usuario::create([
                    'nome' => $googleUser->name ?? $googleUser->email,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'tipo_usuario' => 'Aluno', // Default novo usuário como aluno
                    'remember_token' => Str::random(60),
                    'password' => bcrypt(124)
                ]);

                Alunos::create([
                    'usuario_id' => $user->id
                ]);
            }

            Auth::login($user, true);

            if (!Auth::check()) {
                return redirect('/')->with('error', 'Login falhou após autenticação do Google.');
            }

            // Carrega relacionamento (se for aluno)
            $user->load('aluno');

            // Redireciona com base no tipo de usuário
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
