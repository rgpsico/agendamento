<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiaDaSemana;
use App\Models\Disponibilidade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DisponibilidadeControllerApi extends Controller
{
    // Mostrar todos os usuários
    public function index()
    {
        $users = Disponibilidade::all();
        return response()->json($users);
    }

    public function disponibilidade(Request $request)
    {
        $day = $request->input('day');


        $schedules = Disponibilidade::where('id_dia', $day)->get();

        $timeslots = [];

        foreach ($schedules as $schedule) {
            $start = Carbon::parse($schedule->hora_inicio);
            $end = Carbon::parse($schedule->hora_fim);

            for ($time = $start; $time->lessThan($end); $time->addHour()) {
                $timeslots[] = $time->format('H:i');
            }
        }

        return response()->json($timeslots);
    }


    // Criar um novo usuário
    public function store(Request $request)
    {
        $user = Disponibilidade::create($request->all());
        return response()->json($user, 201);
    }

    // Mostrar um usuário específico
    public function show($id)
    {
        $user = Disponibilidade::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'Disponibilidade não encontrada'], 404);
        }
    }

    // Atualizar um usuário específico
    public function update(Request $request, $id)
    {
        $user = Disponibilidade::find($id);
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
        $user = DiaDaSemana::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => 'Usuário deletado com sucesso'], 200);
        } else {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }
    }
}
