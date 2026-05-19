<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1º tenta tenant resolvido pelo domínio (DetectTenant já rodou)
        $tenant = app()->has('currentTenant') ? app('currentTenant') : null;

        // 2º fallback: empresa do usuário autenticado
        if (! $tenant) {
            $tenant = $request->user()?->empresa;
        }

        abort_unless($tenant, 403, 'Tenant nao identificado.');

        app()->instance('currentTenant', $tenant);
        View::share('currentTenant', $tenant);

        return $next($request);
    }
}
