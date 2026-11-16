<?php

namespace App\Http\Middleware;

use App\Models\Conversation;
use App\Support\EmpresasDoUsuario;
use Closure;
use Illuminate\Http\Request;

/**
 * Rotas de conversa (chat do painel, do aluno e do professor): só o próprio usuário.
 *
 * - Exige usuário logado (sessão do painel ou token Sanctum).
 * - user_id / professor_user_id / aluno_user_id vindos na requisição são trocados pelo usuário logado
 *   (antes qualquer um lia e escrevia como qualquer usuário). Master pode informar outro.
 * - conversation_id (na URL ou no corpo) precisa ser de uma conversa do usuário (ele é o cliente)
 *   ou de uma empresa dele (dono ou funcionário).
 */
class ConversaDoUsuario
{
    private const CAMPOS_USUARIO = ['user_id', 'professor_user_id', 'aluno_user_id'];

    public function handle(Request $request, Closure $next)
    {
        $usuario = $request->user();
        abort_unless($usuario, 401, 'Faça login.');

        if (! $usuario->isMasterUser()) {
            $troca = [];
            foreach (self::CAMPOS_USUARIO as $campo) {
                if ($request->has($campo)) {
                    $troca[$campo] = $usuario->id;
                }
            }
            if ($troca) {
                $request->merge($troca);
                $request->query->add(array_intersect_key($troca, $request->query->all()));
            }

            $conversaId = $request->route('conversationId') ?? $request->route('id') ?? $request->input('conversation_id');
            if ($conversaId !== null && $conversaId !== '') {
                $conversa = Conversation::find((int) $conversaId);
                abort_unless($conversa, 404);

                $daEmpresa = in_array((int) $conversa->empresa_id, EmpresasDoUsuario::ids($usuario), true);
                $doCliente = (int) $conversa->user_id === (int) $usuario->id;
                abort_unless($daEmpresa || $doCliente, 403, 'Conversa de outra empresa.');
            }
        }

        return $next($request);
    }
}
