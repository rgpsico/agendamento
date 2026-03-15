<?php

namespace App\Http\Controllers;

use App\Models\SiteVisitante;
use Illuminate\Http\Request;

class SiteVisitanteController extends Controller
{
    public function store(Request $request)
    {
        $sessionId = session()->getId(); // pega o ID da sessão do usuário

        // Verifica se o visitante já foi registrado
        $existe = SiteVisitante::where('empresa_site_id', $request->empresa_site_id ?? 1)
            ->where('session_id', $sessionId)
            ->first();

        if (!$existe) {
            $visitante = SiteVisitante::create([
                'empresa_site_id' => $request->empresa_site_id ?? 1,
                'session_id' => $sessionId,
            ]);

            return response()->json(['success' => true, 'visitante_id' => $visitante->id]);
        }

        return response()->json(['success' => false, 'message' => 'Visitante já registrado']);
    }
}
