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

    public function professorGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = Usuario::where('email', $googleUser->email)->first();

            if (!$user) {
                // Crie um novo usuário ou modifique conforme suas necessidades
                $user = Usuario::create([
                    'nome' => $googleUser->email, // Aqui você deve provavelmente usar ->name ao invés de ->email
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'tipo_usuario' => 'Professor',
                    'remember_token' => Str::random(60), // Gerar um token aleatório
                    'password' => bcrypt(124) // Isso deve ser atualizado para algo mais seguro mais tarde
                ]);

                Alunos::create([
                    'usuario_id' => $user->id
                ]);
            }

            Auth::login($user, true);


            return redirect()->route('admin.dashboard', ['id' => $user->id]);  // ou onde você deseja redirecionar após o login

        } catch (\Exception $e) {
            dd($e);
            // \Log::error('Erro com autenticação do Google: ' . $e->getMessage());

            dd($e);
            return redirect('/')->with('error', 'Erro ao tentar autenticar com o Google.');
        }
    }
}
