<?php

namespace App\Services\Barbearia;

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

/** Camada fina sobre a biblioteca de Web Push (trocada por um falso nos testes). */
class EnviadorWebPush
{
    public function configurado(): bool
    {
        return filled(config('webpush.public_key')) && filled(config('webpush.private_key'));
    }

    /**
     * Envia o mesmo aviso para várias inscrições.
     *
     * @param  array<int, array{endpoint:string, chave_p256dh:string, chave_auth:string}>  $inscricoes
     * @return array<int, string> endpoints que o serviço de push disse que não existem mais (para apagar)
     */
    public function enviar(array $inscricoes, array $conteudo): array
    {
        if (! $this->configurado() || ! $inscricoes) {
            return [];
        }

        $webPush = new WebPush(['VAPID' => [
            'subject' => config('webpush.subject'),
            'publicKey' => config('webpush.public_key'),
            'privateKey' => config('webpush.private_key'),
        ]], ['TTL' => 3600, 'urgency' => 'high'], 8);

        foreach ($inscricoes as $i) {
            $webPush->queueNotification(Subscription::create([
                'endpoint' => $i['endpoint'],
                'keys' => ['p256dh' => $i['chave_p256dh'], 'auth' => $i['chave_auth']],
                'contentEncoding' => 'aes128gcm', // padrão atual (RFC 8291), aceito por Chrome, Firefox e Safari/iPhone
            ]), json_encode($conteudo, JSON_UNESCAPED_UNICODE));
        }

        $mortos = [];
        foreach ($webPush->flush() as $relatorio) {
            if (! $relatorio->isSuccess() && $relatorio->isSubscriptionExpired()) {
                $mortos[] = $relatorio->getEndpoint();
            }
        }

        return $mortos;
    }
}
