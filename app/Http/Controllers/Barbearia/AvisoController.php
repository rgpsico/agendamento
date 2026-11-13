<?php

namespace App\Http\Controllers\Barbearia;

use App\Http\Controllers\Controller;
use App\Services\Barbearia\AvisoBarbeiro;
use Illuminate\Http\Request;

/**
 * Aviso no celular do barbeiro: inscrição do aparelho, teste, service worker e manifesto do app.
 * O service worker fica em /barbearia/sw.js para ter alcance só sobre /barbearia/.
 */
class AvisoController extends Controller
{
    public function inscrever(Request $request, AvisoBarbeiro $aviso)
    {
        abort_unless(BarbeiroController::doUsuario($request->user()), 403);

        $dados = $request->validate([
            'endpoint' => ['required', 'url', 'max:2000'],
            'keys.p256dh' => ['required', 'string', 'max:255'],
            'keys.auth' => ['required', 'string', 'max:255'],
        ]);

        $aviso->inscrever($request->user(), $dados['endpoint'], $dados['keys']['p256dh'], $dados['keys']['auth'], $request->userAgent());

        return response()->json(['ok' => true]);
    }

    public function cancelar(Request $request, AvisoBarbeiro $aviso)
    {
        $dados = $request->validate(['endpoint' => ['required', 'url', 'max:2000']]);
        $aviso->remover($request->user(), $dados['endpoint']);

        return response()->json(['ok' => true]);
    }

    public function testar(Request $request, AvisoBarbeiro $aviso)
    {
        abort_unless(BarbeiroController::doUsuario($request->user()), 403);

        return response()->json(['enviados' => $aviso->testar($request->user())]);
    }

    public function serviceWorker()
    {
        return response(view('barbearia.sw')->render(), 200, [
            'Content-Type' => 'application/javascript; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Service-Worker-Allowed' => '/barbearia/',
        ]);
    }

    public function manifesto()
    {
        return response()->json([
            'name' => 'Fila do Barbeiro',
            'short_name' => 'Fila',
            'description' => 'Fila de clientes e agenda do barbeiro.',
            'start_url' => '/barbearia/painel/fila',
            'scope' => '/barbearia/',
            'display' => 'standalone',
            'background_color' => '#1f1f1f',
            'theme_color' => '#1f1f1f',
            'lang' => 'pt-BR',
            'icons' => [
                ['src' => '/images/barbearia/icone-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable'],
                ['src' => '/images/barbearia/icone-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
