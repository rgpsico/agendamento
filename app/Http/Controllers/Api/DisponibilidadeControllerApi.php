<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiaDaSemana;
use App\Models\Disponibilidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data_selecionada = $request->input("data_select");
        $professor_id = $request->input("professor_id");
        $servico_id = $request->input("servico_id"); // Novo parâmetro

        // Obtém todas as disponibilidades do serviço selecionado naquele dia
        $schedules = Disponibilidade::where('id_dia', $day)
            ->where('id_servico', $servico_id)
            ->get();

        $timeslots = [];

        foreach ($schedules as $schedule) {
            $start = Carbon::parse($schedule->hora_inicio)->format('H:i');

            // Verifica se o horário já foi agendado
            $alreadyBooked = DB::table('agendamentos')
                ->where('data_da_aula', $data_selecionada)
                ->where('horario', $start)
                ->where('professor_id', $professor_id)
                ->exists();

            if (!$alreadyBooked) {
                $timeslots[] = $start;
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

    public function storepersonalizado(Request $request)
    {
        $id_professor = $request->professor_id;

        // Deleta as disponibilidades atuais para evitar duplicações
        Disponibilidade::where('id_professor', $id_professor)->delete();

        // Percorre os dias e salva múltiplos horários por dia
        foreach ($request->start as $dia => $horariosInicio) {
            foreach ($horariosInicio as $index => $horaInicio) {
                $horaFim = $request->end[$dia][$index] ?? null;

                if ($horaInicio && $horaFim) {
                    Disponibilidade::create([
                        'id_professor' => $id_professor,
                        'id_dia' => $dia,
                        'hora_inicio' => $horaInicio,
                        'hora_fim' => $horaFim,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Disponibilidade atualizada com sucesso!');
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
