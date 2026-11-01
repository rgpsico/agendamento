<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Rotas da API usadas pelo painel autenticam pela sessao do navegador (sem CSRF token).
 * Para nao abrir CSRF, toda escrita precisa vir como requisicao AJAX (X-Requested-With, que o
 * jQuery envia sozinho) ou como JSON: um formulario de outro site nao consegue enviar nenhum dos dois.
 */
class PainelApiWrites
{
    public function handle(Request $request, Closure $next)
    {
        $leitura = in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);

        if (! $leitura && ! $request->ajax() && ! $request->isJson()) {
            abort(403, 'Requisicao invalida.');
        }

        return $next($request);
    }
}
