<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use App\Models\Despesa;
use App\Models\Despesas;

class FinanceiroDashboardController extends Controller
{
       public function index()
    {
        $request = request();

        // Recebe período da requisição ou usa início e fim do mês atual
        $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->get('data_fim', now()->endOfMonth()->format('Y-m-d'));

        // Totais
        $totalReceitas = Receita::whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');
        $totalDespesas = Despesas::whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');

        // Receitas e despesas do período
        $receitasMes = $totalReceitas; // já calculado
        $despesasMes = $totalDespesas;

        // Receitas pendentes e recebidas
        $receitasPendentes = Receita::where('status', 'PENDENTE')
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->count();

        $receitasRecebidas = Receita::where('status', 'RECEBIDA')
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->count();

        $totalReceitasPendentes = Receita::where('status', 'PENDENTE')
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->sum('valor');

        $totalReceitasRecebidas = Receita::where('status', 'RECEBIDA')
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->sum('valor');

        // Despesas pendentes
        $despesasPendentes = Despesas::where('status', 'PENDENTE')
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->count();

        $totalDespesasPendentes = Despesas::where('status', 'PENDENTE')
            ->whereBetween('created_at', [$dataInicio, $dataFim])
            ->sum('valor');

        return view('admin.financeiro.dashboard.dashboard', compact(
            'totalReceitas',
            'totalDespesas',
            'receitasMes',
            'despesasMes',
            'receitasPendentes',
            'receitasRecebidas',
            'totalReceitasPendentes',
            'totalReceitasRecebidas',
            'despesasPendentes',
            'totalDespesasPendentes',
            'dataInicio',
            'dataFim'
        ));
    }


}
