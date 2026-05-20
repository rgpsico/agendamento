<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\EmpresaSite;
use App\Models\NichoConfiguracao;
use Illuminate\Support\Facades\View;

class DetectTenant
{
    /**
     * Detecta o tenant (empresa) e a configuração de nicho pelo domínio.
     * Funciona para rotas públicas e autenticadas.
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        // 1. Configuração de nicho (surf, pilates, etc.) — sempre tenta resolver
        $nichoConfig = NichoConfiguracao::resolveByHost($host);
        if ($nichoConfig) {
            app()->instance('currentNicho', $nichoConfig);
            View::share('currentNicho', $nichoConfig);
        }

        // 2. Tenant (escola específica) pelo domínio personalizado
        $site = EmpresaSite::resolveByHost($host);
        if ($site) {
            $empresa = $site->empresa()->with('modalidade')->first();

            app()->instance('currentSite', $site);
            app()->instance('currentTenant', $empresa);
            config(['app.empresa_id' => $empresa->id]);

            View::share('currentTenant', $empresa);
            View::share('currentSite', $site);
        }

        return $next($request);
    }
}

