<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WidgetEvento;
use App\Models\WidgetSite;
use Illuminate\Http\Request;

class MetricaWidgetController extends Controller
{
    /**
     * POST /api/track/{token}/evento
     * Recebe eventos de rastreamento do script JS externo.
     */
    /** Responde ao preflight OPTIONS sem autenticação */
    public function preflight()
    {
        return $this->corsResponse(response()->noContent());
    }

    public function evento(Request $request, string $token)
    {
        $site = WidgetSite::where('token', $token)->where('ativo', true)->first();

        if (! $site) {
            return $this->corsResponse(response()->json(['ok' => false], 200));
        }

        $request->validate([
            'tipo'        => 'required|string|max:30',
            'session_id'  => 'required|string|max:48',
            'pagina'      => 'nullable|string|max:500',
            'referrer'    => 'nullable|string|max:500',
            'dispositivo' => 'nullable|string|max:10',
            'duracao'     => 'nullable|integer|min:0|max:86400',
            'meta'        => 'nullable|string|max:255',
        ]);

        WidgetEvento::create([
            'site_id'     => $site->id,
            'session_id'  => $request->session_id,
            'tipo'        => $request->tipo,
            'pagina'      => $request->pagina,
            'referrer'    => $request->referrer,
            'dispositivo' => $request->dispositivo ?? 'desktop',
            'duracao'     => $request->duracao,
            'meta'        => $request->meta,
        ]);

        return $this->corsResponse(response()->json(['ok' => true], 200));
    }

    private function corsResponse($response)
    {
        return $response
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Accept');
    }
}
