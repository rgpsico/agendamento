<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgendamentoFrontendController extends BaseApiController
{
    public function byEmpresa(int $empresaId)
    {
        $agendamentos = Agendamento::with(['aluno.usuario', 'servico'])
            ->whereHas('professor', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->orderBy('data_da_aula')
            ->get()
            ->map(function (Agendamento $agendamento) {
                return $this->formatAgendamento($agendamento);
            });

        return $this->success($agendamentos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aluno_id' => 'required|integer|exists:alunos,id',
            'professor_id' => 'required|integer|exists:professores,id',
            'modalidade_id' => 'required|integer|exists:modalidade,id',
            'data_da_aula' => 'required|date',
            'horario' => 'required|string|max:10',
            'valor_aula' => 'required|numeric',
            'servico_id' => 'nullable|integer|exists:servicos,id',
            'status' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }

        $agendamento = new Agendamento();
        $agendamento->aluno_id = $request->input('aluno_id');
        $agendamento->professor_id = $request->input('professor_id');
        $agendamento->modalidade_id = $request->input('modalidade_id');
        $agendamento->data_da_aula = $request->input('data_da_aula');
        $agendamento->horario = $request->input('horario');
        $agendamento->valor_aula = $request->input('valor_aula');
        $agendamento->servico_id = $request->input('servico_id');
        $agendamento->status = $request->input('status', 'Espera');
        $agendamento->save();

        $agendamento->load(['aluno.usuario', 'servico']);

        return $this->success($this->formatAgendamento($agendamento), 'Created', 201);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'aluno_id' => 'sometimes|integer|exists:alunos,id',
            'professor_id' => 'sometimes|integer|exists:professores,id',
            'modalidade_id' => 'sometimes|integer|exists:modalidade,id',
            'data_da_aula' => 'sometimes|date',
            'horario' => 'sometimes|string|max:10',
            'valor_aula' => 'sometimes|numeric',
            'servico_id' => 'sometimes|nullable|integer|exists:servicos,id',
            'status' => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }

        $agendamento = Agendamento::find($id);
        if (!$agendamento) {
            return $this->error('Not found', 404);
        }

        $fields = [
            'aluno_id',
            'professor_id',
            'modalidade_id',
            'data_da_aula',
            'horario',
            'valor_aula',
            'servico_id',
            'status',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $agendamento->{$field} = $request->input($field);
            }
        }

        $agendamento->save();
        $agendamento->load(['aluno.usuario', 'servico']);

        return $this->success($this->formatAgendamento($agendamento));
    }

    public function destroy(int $id)
    {
        $agendamento = Agendamento::find($id);
        if (!$agendamento) {
            return $this->error('Not found', 404);
        }

        $agendamento->delete();

        return $this->success(['id' => $id], 'Deleted');
    }

    private function formatAgendamento(Agendamento $agendamento): array
    {
        $aluno = $agendamento->aluno;
        $usuario = $aluno ? $aluno->usuario : null;
        $servico = $agendamento->servico;

        return [
            'id' => $agendamento->id,
            'aluno_id' => $agendamento->aluno_id,
            'professor_id' => $agendamento->professor_id,
            'modalidade_id' => $agendamento->modalidade_id,
            'data_da_aula' => $this->formatDateTime($agendamento->data_da_aula),
            'horario' => $agendamento->horario,
            'valor_aula' => $agendamento->valor_aula,
            'status' => $agendamento->status,
            'servico_id' => $agendamento->servico_id,
            'created_at' => $this->formatDateTime($agendamento->created_at),
            'updated_at' => $this->formatDateTime($agendamento->updated_at),
            'aluno' => $aluno ? [
                'id' => $aluno->id,
                'usuario_id' => $aluno->usuario_id,
                'avatar' => $aluno->avatar,
                'usuario' => $usuario ? [
                    'id' => $usuario->id,
                    'nome' => $usuario->nome,
                    'email' => $usuario->email,
                    'telefone' => $usuario->telefone,
                    'data_nascimento' => $this->formatDate($usuario->data_nascimento),
                ] : null,
            ] : null,
            'servico' => $servico ? [
                'id' => $servico->id,
                'titulo' => $servico->titulo,
                'preco' => $servico->preco,
                'tempo_de_aula' => $servico->tempo_de_aula,
            ] : null,
        ];
    }

    private function formatDateTime($value): ?string
    {
        if (!$value) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->toDateTimeString();
        }

        return Carbon::parse($value)->toDateTimeString();
    }

    private function formatDate($value): ?string
    {
        if (!$value) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->toDateString();
        }

        return Carbon::parse($value)->toDateString();
    }
}
