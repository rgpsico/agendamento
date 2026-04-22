<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiaDaSemana;
use App\Models\Disponibilidade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Agendamento;
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
        $data_selecionada = $request->input('data_select');
        $professor_id = $request->input('professor_id');
        $servico_id = $request->input('servico_id');

        // Obtém todos os horários já agendados para o professor nessa data
        // Filtra pelo professor (e opcionalmente pelo serviço) para não mostrar slots ocupados
        $horariosAgendados = DB::table('agendamentos')
            ->where('data_da_aula', $data_selecionada)
            ->where('professor_id', $professor_id)
            ->pluck('horario')
            ->map(function($horario) {
                return Carbon::parse($horario)->format('H:i');
            })
            ->toArray();

        // Obtém todas as disponibilidades do serviço selecionado naquele dia da semana
        $schedules = Disponibilidade::where('id_dia', $day)
            ->where('id_servico', $servico_id)
            ->when($professor_id, fn($q) => $q->where('id_professor', $professor_id))
            ->get();

        $timeslots = [];

        foreach ($schedules as $schedule) {
            $start = Carbon::parse($schedule->hora_inicio)->format('H:i');

            if (!in_array($start, $horariosAgendados) && !in_array($start, $timeslots)) {
                $timeslots[] = $start;
            }
        }

        return response()->json($timeslots);
    }

    public function horariosContratados(Request $request)
    {
        $dataSelecionada = $request->input('data_select');
        $professorId = $request->input('professor_id');
        $servicoId = $request->input('servico_id');

        $agendamentos = Agendamento::with(['aluno.usuario'])
            ->where('data_da_aula', $dataSelecionada)
            ->where('professor_id', $professorId)
            ->when($servicoId, function ($query, $servicoId) {
                return $query->where('servico_id', $servicoId);
            })
            ->get();

        $payload = $agendamentos->map(function ($agendamento) {
            $aluno = $agendamento->aluno;
            $usuario = $aluno?->usuario;

            return [
                'agendamento_id' => $agendamento->id,
                'horario' => Carbon::parse($agendamento->horario)->format('H:i'),
                'aluno' => [
                    'id' => $aluno?->id,
                    'usuario_id' => $aluno?->usuario_id,
                    'nome' => $usuario?->nome,
                ],
            ];
        });

        return response()->json($payload);
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
        $id_servico   = $request->servico_id;

        // Deleta apenas registros do escopo correto para evitar apagar dados de outros serviços
        $delete = Disponibilidade::where('id_professor', $id_professor);
        if ($id_servico) {
            $delete->where('id_servico', $id_servico);
        } else {
            $delete->whereNull('id_servico');
        }
        $delete->delete();

        // Percorre os dias e salva múltiplos horários por dia
        foreach ($request->start as $dia => $horariosInicio) {
            foreach ($horariosInicio as $index => $horaInicio) {
                $horaFim = $request->end[$dia][$index] ?? null;

                if ($horaInicio && $horaFim) {
                    Disponibilidade::create([
                        'id_professor' => $id_professor,
                        'id_servico'   => $id_servico ?: null,
                        'id_dia'       => $dia,
                        'hora_inicio'  => $horaInicio,
                        'hora_fim'     => $horaFim,
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
