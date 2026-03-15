<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Receita;
use Illuminate\Http\Request;

class ReceitaApiController extends Controller
{
    /**
     * Listar receitas (com filtros e total).
     */
    public function index()
    {
        $request = request();
        $query = Receita::with(['usuario', 'categoria', 'pagamento.agendamento.modalidade']);

        // Filtros opcionais
        if ($request->aluno) {
            $query->whereHas('usuario', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->aluno . '%');
            });
        }

        if ($request->descricao) {
            $query->where('descricao', 'like', '%' . $request->descricao . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->metodo_pagamento) {
            $query->whereHas('pagamento', function ($q) use ($request) {
                $q->where('metodo_pagamento', $request->metodo_pagamento);
            });
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_vencimento', [$request->data_inicio, $request->data_fim]);
        }

        // Totais filtrados
        $totalReceitas = (clone $query)->sum('valor');

        // Paginação ou tudo
        if ($request->get('all') === 'true') {
            $receitas = $query->orderBy('created_at', 'desc')->get();
        } else {
            $receitas = $query->orderBy('created_at', 'desc')->paginate(15);
        }

        return response()->json([
            'data' => $receitas,
            'total' => $totalReceitas
        ]);
    }

    /**
     * Mostrar uma receita específica.
     */
    public function show($id)
    {
        $receita = Receita::with(['usuario', 'categoria', 'pagamento.agendamento.modalidade'])
            ->findOrFail($id);

        return response()->json($receita);
    }

    public function resumo(Request $request)
    {

        $query = Receita::query();

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_vencimento', [$request->data_inicio, $request->data_fim]);
        }

        $totalRecebidas = (clone $query)->where('status', 'RECEBIDA')->sum('valor');
        $totalPendentes = (clone $query)->where('status', 'PENDENTE')->sum('valor');

        return response()->json([
            'total_recebidas' => $totalRecebidas,
            'total_pendentes' => $totalPendentes,
            'total_geral'     => $totalRecebidas + $totalPendentes,
        ]);
    }
}
