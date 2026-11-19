<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/widget/*',
        'api/bot/*',
        'api/track/*',
        // Formulário público do site da empresa: não depende de login, e com a página aberta por horas
        // o token expirava e o visitante via "não foi possível enviar". Tem honeypot e limite por minuto.
        'site/*/contato',
    ];
}
