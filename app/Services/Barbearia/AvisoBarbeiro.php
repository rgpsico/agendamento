<?php

namespace App\Services\Barbearia;

use App\Models\BarbeariaPushInscricao;
use App\Models\FilaAtendimento;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;

/** Avisa o celular do barbeiro (mesmo com a tela apagada) quando um cliente entra na fila dele. */
class AvisoBarbeiro
{
    public function __construct(private EnviadorWebPush $enviador)
    {
    }

    public function inscrever(Usuario $usuario, string $endpoint, string $p256dh, string $auth, ?string $aparelho = null): BarbeariaPushInscricao
    {
        // o mesmo aparelho pode trocar de dono (outro barbeiro entrou no mesmo celular): fica com quem inscreveu por último
        return BarbeariaPushInscricao::updateOrCreate(
            ['endpoint_hash' => hash('sha256', $endpoint)],
            ['usuario_id' => $usuario->id, 'endpoint' => $endpoint, 'chave_p256dh' => $p256dh, 'chave_auth' => $auth, 'aparelho' => $aparelho ? mb_substr($aparelho, 0, 190) : null]
        );
    }

    public function remover(Usuario $usuario, string $endpoint): void
    {
        BarbeariaPushInscricao::where('usuario_id', $usuario->id)->where('endpoint_hash', hash('sha256', $endpoint))->delete();
    }

    public function novoCliente(FilaAtendimento $fila): void
    {
        $fila->loadMissing('professor', 'aluno.usuario');

        $cliente = optional(optional($fila->aluno)->usuario)->nome ?: 'Um cliente';
        $espera = FilaAtendimento::where('professor_id', $fila->professor_id)->where('status', FilaAtendimento::AGUARDANDO)->count();

        $this->enviarPara((int) optional($fila->professor)->usuario_id, [
            'titulo' => '💈 Cliente na fila',
            'texto' => $cliente . ' fez check-in · ' . ($espera === 1 ? '1 pessoa esperando' : $espera . ' pessoas esperando'),
            'url' => route('barbearia.painel.fila', [], false),
            'tag' => 'fila-' . $fila->professor_id,
        ]);
    }

    public function testar(Usuario $usuario): int
    {
        return $this->enviarPara($usuario->id, [
            'titulo' => '💈 Teste de aviso',
            'texto' => 'Pronto! Você vai receber um aviso assim quando entrar cliente na sua fila.',
            'url' => route('barbearia.painel.fila', [], false),
            'tag' => 'teste-' . $usuario->id,
        ]);
    }

    /** Devolve em quantos aparelhos o aviso foi enviado. Falha de push nunca derruba o check-in. */
    private function enviarPara(int $usuarioId, array $conteudo): int
    {
        if (! $usuarioId || ! $this->enviador->configurado()) {
            return 0;
        }

        try {
            $inscricoes = BarbeariaPushInscricao::where('usuario_id', $usuarioId)->get();
            if ($inscricoes->isEmpty()) {
                return 0;
            }

            $mortos = $this->enviador->enviar($inscricoes->map->only(['endpoint', 'chave_p256dh', 'chave_auth'])->all(), $conteudo);

            if ($mortos) {
                BarbeariaPushInscricao::whereIn('endpoint_hash', array_map(fn ($e) => hash('sha256', $e), $mortos))->delete();
            }

            return $inscricoes->count() - count($mortos);
        } catch (\Throwable $e) {
            Log::warning('Barbearia: aviso push falhou: ' . $e->getMessage());

            return 0;
        }
    }
}
