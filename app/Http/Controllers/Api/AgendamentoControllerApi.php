<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Aulas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoControllerApi extends Controller
{
    // Mostrar todos os usuários
    public function index()
    {
        $users = Agendamento::all();
        return response()->json($users);
    }

    // Criar um novo usuário
    public function store(Request $request)
    {

        $user = Agendamento::create($request->all());
        return response()->json($user, 201);
    }

    // Mostrar um usuário específico
    public function show($id)
    {
        $user = Agendamento::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    // Atualizar um usuário específico
    public function update(Request $request, $id)
    {
        $user = Agendamento::find($id);

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
        $user = Aulas::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => 'Usuário deletado com sucesso'], 200);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }

    public function getAgendamentos()
    {
        $professor_id = Auth::user()->professor->id;

        // Aqui é importante notar a mudança: 'aluno.usuario'
        // Isso indica ao Eloquent para carregar a relação 'usuario' para cada 'aluno' carregado.
        $agendamentos = Agendamento::with('aluno.usuario')
            ->where('professor_id', $professor_id)
            ->get();

        $eventos = $agendamentos->map(function ($agendamento) {
            // Removi o dd() para que o código execute completamente.
            return [
                'title' => $agendamento->aluno->usuario->nome ?? '', // Corretamente acessando o nome do usuário através do aluno
                'start' => Carbon::parse($agendamento->data_da_aula)->format('Y-m-d') . 'T' . $agendamento->horario,
                'color' => '#ff0000' // Uma cor de exemplo.
            ];
        });

        return response()->json($eventos);
    }
}
