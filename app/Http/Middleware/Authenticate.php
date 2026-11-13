<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // barbeiros, donos e clientes da barbearia entram pelo celular, na página de entrada da própria barbearia
        return $request->is('barbearia/*') ? route('barbearia.login') : route('home.index');
    }
}
