<?php

namespace App\Http\Controllers;

use App\Models\SiteVisualizacao;
use Illuminate\Http\Request;

class SiteVisualizacaoController extends Controller
{
    public function store(Request $request)
    {
        $visualizacao = SiteVisualizacao::create([
            'empresa_site_id' => $request->empresa_site_id ?? 1, // ou pegar dinamicamente
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);

        return response()->json([
            'success' => true,
            'visualizacao_id' => $visualizacao->id
        ]);
    }
}
