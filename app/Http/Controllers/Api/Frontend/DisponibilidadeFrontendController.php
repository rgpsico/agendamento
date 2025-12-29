<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Models\Disponibilidade;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisponibilidadeFrontendController extends BaseApiController
{
    public function byEmpresa(int $empresaId)
    {
        $professorIds = Professor::where('empresa_id', $empresaId)->pluck('id');

        $disponibilidades = Disponibilidade::with(['diaDaSemana', 'servico'])
            ->whereIn('id_professor', $professorIds)
            ->orderBy('id_dia')
            ->orderBy('hora_inicio')
            ->get()
            ->map(function (Disponibilidade $disponibilidade) {
                return $this->formatDisponibilidade($disponibilidade);
            });

        return $this->success($disponibilidades);
    }

    public function byProfessor(int $professorId)
    {
        $disponibilidades = Disponibilidade::with(['diaDaSemana', 'servico'])
            ->where('id_professor', $professorId)
            ->orderBy('id_dia')
            ->orderBy('hora_inicio')
            ->get()
            ->map(function (Disponibilidade $disponibilidade) {
                return $this->formatDisponibilidade($disponibilidade);
            });

        return $this->success($disponibilidades);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_professor' => 'required|integer|exists:professores,id',
            'id_dia' => 'required|integer|exists:dias_da_semana,id',
            'hora_inicio' => 'required|string|max:10',
            'hora_fim' => 'required|string|max:10',
            'id_servico' => 'nullable|integer|exists:servicos,id',
            'data' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }

        $disponibilidade = new Disponibilidade();
        $disponibilidade->id_professor = $request->input('id_professor');
        $disponibilidade->id_dia = $request->input('id_dia');
        $disponibilidade->hora_inicio = $request->input('hora_inicio');
        $disponibilidade->hora_fim = $request->input('hora_fim');
        $disponibilidade->id_servico = $request->input('id_servico');
        $disponibilidade->data = $request->input('data');
        $disponibilidade->save();

        $disponibilidade->load(['diaDaSemana', 'servico']);

        return $this->success($this->formatDisponibilidade($disponibilidade), 'Created', 201);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'id_professor' => 'sometimes|integer|exists:professores,id',
            'id_dia' => 'sometimes|integer|exists:dias_da_semana,id',
            'hora_inicio' => 'sometimes|string|max:10',
            'hora_fim' => 'sometimes|string|max:10',
            'id_servico' => 'sometimes|nullable|integer|exists:servicos,id',
            'data' => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }

        $disponibilidade = Disponibilidade::find($id);
        if (!$disponibilidade) {
            return $this->error('Not found', 404);
        }

        $fields = [
            'id_professor',
            'id_dia',
            'hora_inicio',
            'hora_fim',
            'id_servico',
            'data',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $disponibilidade->{$field} = $request->input($field);
            }
        }

        $disponibilidade->save();
        $disponibilidade->load(['diaDaSemana', 'servico']);

        return $this->success($this->formatDisponibilidade($disponibilidade));
    }

    public function destroy(int $id)
    {
        $disponibilidade = Disponibilidade::find($id);
        if (!$disponibilidade) {
            return $this->error('Not found', 404);
        }

        $disponibilidade->delete();

        return $this->success(['id' => $id], 'Deleted');
    }

    private function formatDisponibilidade(Disponibilidade $disponibilidade): array
    {
        $dia = $disponibilidade->diaDaSemana;
        $servico = $disponibilidade->servico;

        return [
            'id' => $disponibilidade->id,
            'id_professor' => $disponibilidade->id_professor,
            'id_dia' => $disponibilidade->id_dia,
            'hora_inicio' => $disponibilidade->hora_inicio,
            'hora_fim' => $disponibilidade->hora_fim,
            'id_servico' => $disponibilidade->id_servico,
            'data' => $disponibilidade->data,
            'created_at' => $disponibilidade->created_at ? $disponibilidade->created_at->toDateTimeString() : null,
            'updated_at' => $disponibilidade->updated_at ? $disponibilidade->updated_at->toDateTimeString() : null,
            'dia' => $dia ? [
                'id' => $dia->id,
                'nome_dia' => $dia->nome_dia,
            ] : null,
            'servico' => $servico ? [
                'id' => $servico->id,
                'titulo' => $servico->titulo,
                'preco' => $servico->preco,
                'tempo_de_aula' => $servico->tempo_de_aula,
            ] : null,
        ];
    }
}
