<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DisponibilidadeServico;
use App\Models\Servico;

class DisponibilidadeServicoController extends Controller
{
    /**
     * Obtém a disponibilidade de um serviço para uma data específica.
     */
    public function getDisponibilidade(Request $request)
    {
        $request->validate([
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date',
        ]);

        $disponibilidade = DisponibilidadeServico::where('servico_id', $request->servico_id)
            ->where('data', $request->data)
            ->first();

        if (!$disponibilidade) {
            return response()->json([
                'vagas_disponiveis' => 0,
                'mensagem' => 'Nenhuma vaga disponível para essa data.'
            ]);
        }

        return response()->json([
            'vagas_disponiveis' => $disponibilidade->vagasDisponiveis()
        ]);
    }

    /**
     * Atualiza as vagas disponíveis para um serviço em uma data específica.
     * Pode ser usado para aumentar ou diminuir as vagas.
     */
    public function updateDisponibilidade(Request $request)
    {
        $request->validate([
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date',
            'vagas_totais' => 'required|integer|min:1',
        ]);

        $disponibilidade = DisponibilidadeServico::updateOrCreate(
            [
                'servico_id' => $request->servico_id,
                'data' => $request->data
            ],
            [
                'vagas_totais' => $request->vagas_totais
            ]
        );

        return response()->json([
            'success' => true,
            'mensagem' => 'Disponibilidade atualizada com sucesso.',
            'vagas_totais' => $disponibilidade->vagas_totais
        ]);
    }

    /**
     * Reserva uma vaga para um serviço na data específica.
     */
    public function reservarVaga(Request $request)
    {
        $request->validate([
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date',
        ]);

        $disponibilidade = DisponibilidadeServico::where('servico_id', $request->servico_id)
            ->where('data', $request->data)
            ->first();

        if (!$disponibilidade || $disponibilidade->vagasDisponiveis() <= 0) {
            return response()->json([
                'success' => false,
                'mensagem' => 'Não há vagas disponíveis para essa data.'
            ], 400);
        }

        $disponibilidade->increment('vagas_reservadas');

        return response()->json([
            'success' => true,
            'mensagem' => 'Vaga reservada com sucesso.',
            'vagas_disponiveis' => $disponibilidade->vagasDisponiveis()
        ]);
    }
}
