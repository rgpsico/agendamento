<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Despesas;
use Illuminate\Http\Request;

class DespesasApiController extends Controller
{
    /**
     * Listar despesas (com filtros e total).
     */
    public function index()
    {
        $request = request();

        $query = Despesas::with(['usuario', 'categoria', 'empresa']);

        // Filtros opcionais
        if ($request->descricao) {
            $query->where('descricao', 'like', '%' . $request->descricao . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_vencimento', [$request->data_inicio, $request->data_fim]);
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
     * Mostrar uma despesa específica.
     */
    public function show($id)
    {
        $despesa = Despesas::with(['usuario', 'categoria', 'empresa'])
            ->findOrFail($id);

        return response()->json($despesa);
    }

    /**
     * Resumo financeiro de despesas.
     */
    public function resumo(Request $request)
    {
        $query = Despesas::query();

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_vencimento', [$request->data_inicio, $request->data_fim]);
        }

        $totalPagas = (clone $query)->where('status', 'PAGA')->sum('valor');
        $totalPendentes = (clone $query)->where('status', 'PENDENTE')->sum('valor');

        return response()->json([
            'total_pagas' => $totalPagas,
            'total_pendentes' => $totalPendentes,
            'total_geral' => $totalPagas + $totalPendentes,
        ]);
    }
}
