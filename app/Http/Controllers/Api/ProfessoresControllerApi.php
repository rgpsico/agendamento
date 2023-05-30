<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ProfessoresControllerApi extends Controller
{
    // Mostrar todos os usuários
    public function index()
    {
        $users = Usuario::all();
        return response()->json($users);
    }

    // Criar um novo usuário
    public function store(Request $request)
    {
        $user = Usuario::create($request->all());


        $professores = Professor::create(
            [
                'usuario_id' => $user->id,
                'especialidade' => $request->tipo_usuario
            ]
        );



        return response()->json($user, 201);
    }

    // Mostrar um usuário específico
    public function show($id)
    {
        $user = Usuario::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    // Atualizar um usuário específico
    public function update(Request $request, $id)
    {
        $user = Usuario::find($id);
        if ($user) {
            $user->update($request->all());
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    // Deletar um usuário específico
    public function destroy($id)
    {
        $user = Usuario::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => 'Usuário deletado com sucesso'], 200);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    public function aulas($id)
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return response()->json(['message' => 'Professor not found'], 404);
        }

        $aulas = $professor->aulas;

        return response()->json($aulas);
    }
}
