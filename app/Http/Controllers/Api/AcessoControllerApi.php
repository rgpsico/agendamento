<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alunos;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AcessoControllerApi extends Controller
{
    public function verificar(Request $request)
    {
        $validated = $request->validate([
            'aluno_id' => ['required', 'integer', 'exists:alunos,id'],
        ]);

        $aluno = Alunos::with([
            'planos' => function ($query) {
                $query->orderByDesc('aluno_planos.data_fim')
                    ->orderByDesc('aluno_planos.created_at');
            },
        ])->find($validated['aluno_id']);

        if (!$aluno || $aluno->planos->isEmpty()) {
            return response()->json([
                'status' => 'bloqueado',
                'message' => 'Aluno não possui um plano vinculado.',
            ]);
        }

        $planoAtual = $aluno->planos->first();
        $pivot = $planoAtual->pivot;
        $dataFim = $pivot->data_fim ? Carbon::parse($pivot->data_fim) : null;

        if ($pivot->status !== 'ativo') {
            return response()->json([
                'status' => 'bloqueado',
                'message' => 'Plano do aluno está com status inativo ou cancelado.',
                'plano' => [
                    'id' => $planoAtual->id,
                    'nome' => $planoAtual->nome,
                    'status' => $pivot->status,
                    'data_fim' => $pivot->data_fim,
                ],
            ]);
        }

        if ($dataFim && $dataFim->endOfDay()->isPast()) {
            return response()->json([
                'status' => 'bloqueado',
                'message' => 'Plano do aluno está vencido.',
                'plano' => [
                    'id' => $planoAtual->id,
                    'nome' => $planoAtual->nome,
                    'status' => $pivot->status,
                    'data_fim' => $pivot->data_fim,
                ],
            ]);
        }

        return response()->json([
            'status' => 'liberado',
            'message' => $dataFim
                ? 'Plano válido até ' . $dataFim->translatedFormat('d/m/Y') . '.'
                : 'Plano ativo sem data de vencimento definida.',
            'plano' => [
                'id' => $planoAtual->id,
                'nome' => $planoAtual->nome,
                'status' => $pivot->status,
                'data_fim' => $pivot->data_fim,
            ],
        ]);
    }
}
