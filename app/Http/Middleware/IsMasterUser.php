<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsMasterUser
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check() || ! auth()->user()->isMasterUser()) {
            abort(403, 'Acesso restrito.');
        }

        return $next($request);
    }
}
