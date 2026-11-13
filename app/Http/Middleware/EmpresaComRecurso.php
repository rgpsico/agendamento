<?php

namespace App\Http\Middleware;

use App\Models\Empresa;
use App\Models\NichoConfiguracao;
use Closure;
use Illuminate\Http\Request;

/**
 * Só deixa abrir a página se a empresa da rota é de um nicho com a função ligada (ex.: fila_checkin) e está ativa.
 * Vale pela modalidade da empresa, não pelo domínio acessado.
 */
class EmpresaComRecurso
{
    public function handle(Request $request, Closure $next, string $recurso)
    {
        $empresa = $request->route('empresa');

        $nicho = $empresa instanceof Empresa ? NichoConfiguracao::daEmpresa($empresa) : null;

        abort_unless($nicho && $nicho->ativo && $nicho->recurso($recurso) && $empresa->status === 'ativo', 404);

        $request->attributes->set('nicho', $nicho);

        return $next($request);
    }
}
