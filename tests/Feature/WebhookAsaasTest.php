<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebhookAsaasTest extends TestCase
{
    use RefreshDatabase;

    public static function webhooks(): array
    {
        // so os que confirmam pagamento; /api/pix-webhook apenas registra no log
        return [['/api/web-hook'], ['/api/boleto/web-hook']];
    }

    /** @dataProvider webhooks */
    public function test_recusa_token_errado_ou_ausente(string $url): void
    {
        config(['services.asaas.webhook_token' => 'segredo-forte']);
        $payload = ['event' => 'PAYMENT_RECEIVED', 'payment' => ['id' => 'pay_1']];

        $this->postJson($url, $payload, ['asaas-access-token' => '123456@'])->assertUnauthorized();
        $this->postJson($url, $payload)->assertUnauthorized();
    }

    /** @dataProvider webhooks */
    public function test_token_configurado_passa_da_validacao(string $url): void
    {
        config(['services.asaas.webhook_token' => 'segredo-forte']);

        $status = $this->postJson($url, ['event' => 'X', 'payment' => ['id' => 'pay_1']], ['asaas-access-token' => 'segredo-forte'])->getStatusCode();

        $this->assertNotSame(401, $status);
    }
}
