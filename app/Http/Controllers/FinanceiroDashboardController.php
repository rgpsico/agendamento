<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use App\Models\ReceitaRecorrente;
use App\Models\DespesaRecorrente;
use App\Models\Despesas;

class FinanceiroDashboardController extends Controller
{
       
public function index()
{
    $request = request();


    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->endOfMonth()->format('Y-m-d'));
    $tipos = $request->get('tipo', ['todos']);

    // Filtros tipo
    $filtro = $request->get('tipo', 'todos'); // receitas, despesas, receitas_recorrentes, despesas_recorrentes, todos

    $request = request();
    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->endOfMonth()->format('Y-m-d'));
    $tipos = $request->get('tipo', ['todos']);
    // Inicializa totais
    $totalReceitas = $totalDespesas = 0;
    $totalReceitasRecorrentes = $totalDespesasRecorrentes = 0;
    $receitasPendentes = $receitasRecebidas = 0;
    $totalReceitasPendentes = $totalReceitasRecebidas = 0;
    $despesasPendentes = 0;
    $totalDespesasPendentes = 0;

    $receitasMes = 0;
    $despesasMes = 0;
    $totalReceitas = 0;
    $totalDespesas = 0;
    $totalReceitasPendentes = 0;
    $totalReceitasRecebidas = 0;
    $receitasPendentes = 0;
    $receitasRecebidas = 0;
    $despesasPendentes = 0;
    $totalDespesasPendentes = 0;
    $totalReceitasRecorrentes = 0;
    $totalDespesasRecorrentes = 0;

    // Receitas não recorrentes
    if (in_array($filtro, ['todos', 'receitas'])) {
        $receitas = Receita::whereBetween('created_at', [$dataInicio, $dataFim]);
        $totalReceitas = $receitas->sum('valor');

        $receitasPendentes = $receitas->where('status', 'PENDENTE')->count();
        $receitasRecebidas = $receitas->where('status', 'RECEBIDA')->count();

        $totalReceitasPendentes = $receitas->where('status', 'PENDENTE')->sum('valor');
        $totalReceitasRecebidas = $receitas->where('status', 'RECEBIDA')->sum('valor');
    }

    // Despesas não recorrentes
    if (in_array($filtro, ['todos', 'despesas'])) {
        $despesas = Despesas::whereBetween('created_at', [$dataInicio, $dataFim]);
        $totalDespesas = $despesas->sum('valor');
        $despesasPendentes = $despesas->where('status', 'PENDENTE')->count();
        $totalDespesasPendentes = $despesas->where('status', 'PENDENTE')->sum('valor');
    }

    // Receitas recorrentes
    if (in_array($filtro, ['todos', 'receitas_recorrentes'])) {
        $receitasRecorrentes = ReceitaRecorrente::where(function($query) use ($dataInicio, $dataFim) {
            $query->whereBetween('data_inicio', [$dataInicio, $dataFim])
                  ->orWhere(function($q) use ($dataInicio, $dataFim){
                      $q->where('data_inicio', '<', $dataInicio)
                        ->where(function($q2) use ($dataFim){
                            $q2->whereNull('data_fim')->orWhere('data_fim', '>=', now());
                        });
                  });
        });
        $totalReceitasRecorrentes = $receitasRecorrentes->sum('valor');
    }

    // Despesas recorrentes
    if (in_array($filtro, ['todos', 'despesas_recorrentes'])) {
        $despesasRecorrentes = DespesaRecorrente::where(function($query) use ($dataInicio, $dataFim) {
            $query->whereBetween('data_inicio', [$dataInicio, $dataFim])
                  ->orWhere(function($q) use ($dataInicio, $dataFim){
                      $q->where('data_inicio', '<', $dataInicio)
                        ->where(function($q2){
                            $q2->whereNull('data_fim')->orWhere('data_fim', '>=', now());
                        });
                  });
        });
        $totalDespesasRecorrentes = $despesasRecorrentes->sum('valor');
    }

    // Receitas normais
if (in_array('todos', $tipos) || in_array('receitas', $tipos)) {
    $totalReceitas = Receita::whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');
    $receitasMes = $totalReceitas;
    $receitasPendentes = Receita::where('status', 'PENDENTE')->whereBetween('created_at', [$dataInicio, $dataFim])->count();
    $receitasRecebidas = Receita::where('status', 'RECEBIDA')->whereBetween('created_at', [$dataInicio, $dataFim])->count();
    $totalReceitasPendentes = Receita::where('status', 'PENDENTE')->whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');
    $totalReceitasRecebidas = Receita::where('status', 'RECEBIDA')->whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');
}

    // Despesas normais
    if (in_array('todos', $tipos) || in_array('despesas', $tipos)) {
        $totalDespesas = Despesas::whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');
        $despesasMes = $totalDespesas;
        $despesasPendentes = Despesas::where('status', 'PENDENTE')->whereBetween('created_at', [$dataInicio, $dataFim])->count();
        $totalDespesasPendentes = Despesas::where('status', 'PENDENTE')->whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');
    }

    // Receitas Recorrentes
    if (in_array('todos', $tipos) || in_array('receitas_recorrentes', $tipos)) {
        $totalReceitasRecorrentes = ReceitaRecorrente::whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');
    }

    // Despesas Recorrentes
    if (in_array('todos', $tipos) || in_array('despesas_recorrentes', $tipos)) {
        $totalDespesasRecorrentes = DespesaRecorrente::whereBetween('created_at', [$dataInicio, $dataFim])->sum('valor');
    }

    $tipoSelecionado = request()->get('tipo', ['todos']);

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
    'totalReceitasRecorrentes',
    'totalDespesasRecorrentes',
    'dataInicio',
    'dataFim',
    'tipoSelecionado'
));
}


}
