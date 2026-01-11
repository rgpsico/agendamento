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

    public function byProfessor($professorId)
    {
        $agendamentos = Agendamento::with('aluno.usuario')
            ->where('professor_id', $professorId)
            ->get();
        return response()->json($agendamentos);
    }

    public function byEmpresa($empresaId)
    {
        $agendamentos = Agendamento::with('aluno.usuario')
            ->whereHas('professor', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->get();

        return response()->json($agendamentos);
    }

    public function byDia(Request $request)
    {
        $data = $request->query('data', Carbon::today()->toDateString());
        $horario = $request->query('horario');
        $status = $request->query('status');

        $query = Agendamento::with('aluno.usuario')
            ->whereDate('data_da_aula', $data);

        if ($horario) {
            $query->where('horario', '>=', $horario);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $agendamentos = $query->orderBy('horario')->get();

        return response()->json($agendamentos);
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
        $agendamento = Agendamento::find($id);
        if (!$agendamento) {
            return response()->json(['error' => 'Agendamento nao encontrado'], 404);
        }

        $user = Auth::user();
        if (!$user) {
            
            return response()->json(['error' => 'Nao autenticado'], 401);
        }

        $aluno = $user->aluno;
        $professor = $user->professor;

        if ($aluno && $agendamento->aluno_id !== $aluno->id) {
            return response()->json(['error' => 'Sem permissao para cancelar este agendamento'], 403);
        }

        if ($professor && $agendamento->professor_id !== $professor->id) {
            return response()->json(['error' => 'Sem permissao para cancelar este agendamento'], 403);
        }

        if (!$aluno && !$professor) {
            return response()->json(['error' => 'Sem permissao para cancelar este agendamento'], 403);
        }

        $agendamento->delete();
        return response()->json(['success' => 'Agendamento deletado com sucesso'], 200);
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
