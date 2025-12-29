<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Models\Alunos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AlunoFrontendController extends BaseApiController
{
    public function byEmpresa(int $empresaId)
    {
        $alunos = Alunos::with(['usuario', 'endereco'])
            ->whereHas('professores', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->get()
            ->map(function (Alunos $aluno) {
                return $this->formatAluno($aluno);
            });

        return $this->success($alunos);
    }

    public function updateEndereco(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'cep' => 'required|string|max:10',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|max:2',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation failed', 422, $validator->errors());
        }

        $aluno = Alunos::find($id);
        if (!$aluno) {
            return $this->error('Not found', 404);
        }

        $endereco = $aluno->endereco()->updateOrCreate(
            ['aluno_id' => $aluno->id],
            [
                'cep' => $request->input('cep'),
                'endereco' => $request->input('endereco'),
                'cidade' => $request->input('cidade'),
                'estado' => $request->input('estado'),
            ]
        );

        return $this->success([
            'aluno_id' => $aluno->id,
            'endereco' => $endereco,
        ], 'Updated');
    }

    private function formatAluno(Alunos $aluno): array
    {
        $usuario = $aluno->usuario;
        $endereco = $aluno->endereco;

        return [
            'id' => $aluno->id,
            'usuario_id' => $aluno->usuario_id,
            'avatar' => $aluno->avatar,
            'usuario' => $usuario ? [
                'id' => $usuario->id,
                'nome' => $usuario->nome,
                'email' => $usuario->email,
                'telefone' => $usuario->telefone,
                'data_nascimento' => $usuario->data_nascimento ? $usuario->data_nascimento->toDateString() : null,
            ] : null,
            'endereco' => $endereco ? [
                'id' => $endereco->id,
                'endereco' => $endereco->endereco,
                'cidade' => $endereco->cidade,
                'estado' => $endereco->estado,
                'cep' => $endereco->cep,
            ] : null,
        ];
    }
}
