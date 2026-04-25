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
        $tenant = $request->user()?->empresa;

        abort_unless($tenant, 403, 'Tenant nao identificado para o usuario autenticado.');

        app()->instance('currentTenant', $tenant);
        View::share('currentTenant', $tenant);

        return $next($request);
    }
}
