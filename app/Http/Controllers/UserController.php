<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

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

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required|unique:users,email',
            'senha' => 'required',
        ]);


        $user = new Usuario;
        $user->nome = $request->nome;
        $user->email = $request->email;
        $user->senha = Hash::make($request->senha); // Hashing password
        $user->tipo_usuario = 'professor';

        $user->save();

        return redirect()->route('escola.dashboard')->with('success', 'Usuário criado com sucesso!');
    }

    public function show($id)
    {
        $user = Usuario::find($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
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
