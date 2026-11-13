<?php

namespace App\Http\Controllers\Barbearia;

use App\Http\Controllers\Controller;
use App\Models\Alunos;
use App\Models\NichoConfiguracao;
use App\Models\Usuario;
use App\Support\Telefone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/** Cadastro rápido e login do cliente pelo celular (cliente de barbearia não tem paciência para formulário). */
class ClienteController extends Controller
{
    public function cadastro(Request $request)
    {
        $request->merge(['telefone' => Telefone::normalizar($request->input('telefone'))]);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'min:2', 'max:120'],
            'telefone' => ['required', 'digits_between:10,11'],
            'senha' => ['required', 'string', 'min:4', 'max:60'],
        ], [
            'nome.required' => 'Digite seu nome.',
            'telefone.required' => 'Digite o celular com DDD.',
            'telefone.digits_between' => 'Digite o celular com DDD, ex: 21 99999-9999.',
            'senha.min' => 'A senha precisa ter pelo menos 4 caracteres.',
        ]);

        if (Usuario::where('telefone', $dados['telefone'])->exists()) {
            return back()->withInput($request->except('senha'))
                ->withErrors(['telefone' => 'Este celular já tem cadastro. Use "Entrar" com a sua senha.'], 'cadastro');
        }

        $usuario = DB::transaction(function () use ($dados) {
            $usuario = Usuario::forceCreate([
                'nome' => $dados['nome'],
                'email' => Telefone::emailFicticio($dados['telefone']),
                'password' => Hash::make($dados['senha']),
                'tipo_usuario' => 'Aluno',
                'telefone' => $dados['telefone'],
            ]);
            Alunos::create(['usuario_id' => $usuario->id]);

            return $usuario;
        });

        Auth::login($usuario, true);
        $request->session()->regenerate();

        return redirect($this->voltar($request));
    }

    /** Página de entrada direta (barbeiro, dono ou cliente), sem precisar escolher a barbearia antes. */
    public function login(Request $request)
    {
        if ($request->user()) {
            return redirect($request->user()->tipo_usuario === 'Aluno' ? route('barbearia.index') : route('barbearia.painel'));
        }

        $nicho = NichoConfiguracao::where('ativo', true)->get()->first(fn ($n) => $n->recurso('fila_checkin'));

        return view('barbearia.entrar', ['nicho' => $nicho]);
    }

    public function entrar(Request $request)
    {
        $request->validate(['telefone' => 'required', 'senha' => 'required']);

        $telefone = Telefone::normalizar($request->input('telefone'));
        // o mesmo celular pode estar em mais de uma conta antiga: vale a que bater com a senha
        $usuario = $telefone === '' ? null : Usuario::where('telefone', $telefone)->get()
            ->first(fn ($u) => Hash::check($request->input('senha'), $u->password));

        if (! $usuario) {
            return back()->withInput($request->only('telefone'))
                ->withErrors(['telefone' => 'Celular ou senha incorretos.'], 'entrar');
        }

        Auth::login($usuario, true);
        $request->session()->regenerate();

        // dono e barbeiros vão para o painel; cliente volta para onde estava
        return redirect($usuario->tipo_usuario === 'Aluno' ? $this->voltar($request) : route('barbearia.painel'));
    }

    public function sair(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('barbearia.index');
    }

    /** Só volta para páginas da própria barbearia (nunca para um endereço de fora). */
    private function voltar(Request $request): string
    {
        $destino = (string) $request->input('voltar');

        return preg_match('#^/barbearia(/[A-Za-z0-9_\-]+)*$#', $destino) ? $destino : route('barbearia.index', [], false);
    }
}
