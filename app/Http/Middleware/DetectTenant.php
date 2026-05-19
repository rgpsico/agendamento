<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\EmpresaSite;
use Illuminate\Support\Facades\View;

class DetectTenant
{
    /**
     * Detecta o tenant (empresa) pelo domínio da requisição.
     * Funciona para rotas públicas e autenticadas.
     */
    public function handle(Request $request, Closure $next)
    {
        $site = EmpresaSite::resolveByHost($request->getHost());

        if ($site) {
            $empresa = $site->empresa()->with('modalidade')->first();

            // Disponibiliza globalmente na aplicação
            app()->instance('currentSite', $site);
            app()->instance('currentTenant', $empresa);

            // Facilita acesso em controllers sem injeção manual
            config(['app.empresa_id' => $empresa->id]);

            // Compartilha com views Blade (se houver alguma)
            View::share('currentTenant', $empresa);
            View::share('currentSite', $site);
        }

        return $next($request);
    }
}
