<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Receitas;
use App\Models\Despesas;

class FinanceiroDashboardController extends Controller
{
    public function index()
    {
        $totalReceitas = Receitas::sum('valor');
        $totalDespesas = Despesas::sum('valor');
        $saldo = $totalReceitas - $totalDespesas;

        $ultimasReceitas = Receitas::latest()->take(5)->get();
        $ultimasDespesas = Despesas::latest()->take(5)->get();

        return Inertia::render('Financeiro/Dashboard', [
            'totalReceitas' => $totalReceitas,
            'totalDespesas' => $totalDespesas,
            'saldo' => $saldo,
            'ultimasReceitas' => $ultimasReceitas,
            'ultimasDespesas' => $ultimasDespesas,
        ]);
    }
}
