<?php

namespace App\Http\Middleware;

use App\Support\Modulos;
use Closure;
use Illuminate\Http\Request;

/**
 * Bloqueia o funcionário nas áreas do painel que o dono não liberou (ver App\Support\Modulos).
 * Dono, master, aluno e visitante não são afetados.
 */
class VerificaModulo
{
    public function handle(Request $request, Closure $next)
    {
        $usuario = $request->user();

        if (! $usuario instanceof \App\Models\Usuario) {
            return $next($request);
        }

        $modulo = Modulos::daRequisicao($request);

        if ($modulo && ! Modulos::pode($usuario, $modulo)) {
            $area = Modulos::AREAS[$modulo][0];
            $mensagem = "Você não tem acesso à área \"{$area}\". Peça ao responsável pelo estúdio para liberar.";

            if ($request->expectsJson() || str_starts_with($request->path(), 'api/')) {
                return response()->json(['message' => $mensagem], 403);
            }

            abort(403, $mensagem);
        }

        return $next($request);
    }
}
