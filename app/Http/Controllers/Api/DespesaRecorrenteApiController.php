<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DespesaRecorrente;
use Illuminate\Http\Request;

class DespesaRecorrenteApiController extends Controller
{
    /**
     * Listar despesas recorrentes (com filtros e total).
     */
    public function index()
    {
        $request = request();
        $query = DespesaRecorrente::with(['usuario', 'categoria', 'empresa']);

        // Filtros opcionais
        if ($request->descricao) {
            $query->where('descricao', 'like', '%' . $request->descricao . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('data_inicio', [$request->data_inicio, $request->data_fim])
                    ->orWhereBetween('data_fim', [$request->data_inicio, $request->data_fim]);
            });
        }

        // Totais filtrados
        $totalDespesas = (clone $query)->sum('valor');

        // Paginação ou tudo
        if ($request->get('all') === 'true') {
            $despesas = $query->orderBy('created_at', 'desc')->get();
        } else {
            $despesas = $query->orderBy('created_at', 'desc')->paginate(15);
        }

        return response()->json([
            'data' => $despesas,
            'total' => $totalDespesas
        ]);
    }

    /**
     * Mostrar uma despesa recorrente específica.
     */
    public function show($id)
    {
        $despesa = DespesaRecorrente::with(['usuario', 'categoria', 'empresa'])
            ->findOrFail($id);

        return response()->json($despesa);
    }

    /**
     * Resumo financeiro de despesas recorrentes.
     */
    public function resumo(Request $request)
    {

        $query = DespesaRecorrente::query();

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('data_inicio', [$request->data_inicio, $request->data_fim])
                    ->orWhereBetween('data_fim', [$request->data_inicio, $request->data_fim]);
            });
        }

        $totalAtivas = (clone $query)->where('status', 'ACTIVE')->sum('valor');
        $totalInativas = (clone $query)->where('status', 'INACTIVE')->sum('valor');

        return response()->json([
            'total_ativas' => $totalAtivas,
            'total_inativas' => $totalInativas,
            'total_geral' => $totalAtivas + $totalInativas,
        ]);
    }
}
