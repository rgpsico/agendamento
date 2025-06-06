<?php

namespace App\Http\Controllers;

use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Stripe\Charge;

class UserController extends Controller
{
    public function index()
    {
        $users = Usuario::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function profile($empresaId)
    {
        $model = Empresa::where('empresa_id', $empresaId)->get();
        return view('users.profile');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home.index');
    }

    public function store(Request $request)
    {


        $request->validate([
            'nome' => 'required',
            'email' => 'required|unique:usuarios,email',
            'senha' => 'required',
        ]);

        // Stripe::setApiKey(env('STRIPE_SECRET'));


        // $account = \Stripe\Account::create([
        //     'type' => 'standard',
        //     'country' => 'US',
        //     'email' => 'rgdogalo10@hotmail.com',
        //     'business_type' => 'individual',
        //     'individual' => [
        //         'first_name' => 'roger',
        //         'last_name' => 'neves',
        //     ],
        // ]);

        // $accountId = $account->id;
        // dd($accountId);

        $user = new Usuario;
        $user->nome = $request->nome;
        $user->email = $request->email;
        $user->password = Hash::make($request->senha); // Hashing password
        $user->tipo_usuario = $request->tipo_usuario;

        $user->save();

        if ($user) {
            Auth::login($user);

            if ($user->tipo_usuario == 'Professor') {

                // Crie um professor associado ao usuário.
                Professor::create([
                    'usuario_id' => $user->id,
                    'modalidade_id' => $request->modalidade_id,
                    'sobre' => 'SObre default',
                    'avatar' => 'AVATAR'
                ]);

                if (!Auth::user()->empresa) {
                    return redirect()->route('empresa.configuracao', ['userId' => $user->id]);
                }
            }

            if ($user->tipo_usuario == 'Aluno') {
                // Crie um aluno associado ao usuário.
                Alunos::create([
                    'usuario_id' => $user->id,
                ]);

                return redirect()->route('alunos.aulas')->with('success', 'Usuário criado com sucesso!');
            }
        }
        return redirect()->back();
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|min:1'
        ]);



        if (Auth::attempt(['email' => $request->email, 'password' => $request->senha])) { // Changed 'senha' to 'password'

            if (Auth::user()->tipo_usuario == 'Professor') {
                return redirect()->intended(route('cliente.dashboard'));
            }

            return redirect()->intended(route('alunos.aulas'));
        }

        // If unsuccessful, redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email'))->withErrors(['Senha inválida']);
    }


    public function show($id)
    {
        $user = Usuario::find($id);

        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        dd('aaa');
        $user = Usuario::find($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'senha' => 'required',
            'tipo_usuario' => 'required',
        ]);

        $user = Usuario::find($id);
        $user->nome = $request->nome;
        $user->email = $request->email;
        if ($request->senha) {
            $user->senha = Hash::make($request->senha); // Hashing password if changed
        }
        $user->tipo_usuario = $request->tipo_usuario;

        $user->save();

        return redirect('/users')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = Usuario::find($id);
        $user->delete();

        return redirect('/users')->with('success', 'Usuário deletado com sucesso!');
    }
}
