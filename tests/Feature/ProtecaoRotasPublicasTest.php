<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Twilio\Security\RequestValidator;

/** Rotas publicas da API: IP real do visitante, limites de uso e webhooks do Twilio assinados. */
class ProtecaoRotasPublicasTest extends TestCase
{
    /** Chamada como se viesse do nginx_proxy_manager (172.19.x) em nome de um visitante. */
    private function como(string $ipVisitante, string $proxy = '172.19.0.5')
    {
        return $this->withServerVariables(['REMOTE_ADDR' => $proxy, 'HTTP_X_FORWARDED_FOR' => $ipVisitante, 'HTTP_X_FORWARDED_PROTO' => 'https']);
    }

    public function test_enxerga_o_ip_do_visitante_atras_do_proxy_do_docker(): void
    {
        Route::get('/_teste_ip', fn (Request $r) => response()->json(['ip' => $r->ip(), 'https' => $r->secure()]));

        $this->como('203.0.113.7')->getJson('/_teste_ip')->assertJson(['ip' => '203.0.113.7', 'https' => true]);
        // cliente forjando o cabecalho: vale o ultimo IP que o proxy confiavel viu
        $this->como('6.6.6.6, 203.0.113.7')->getJson('/_teste_ip')->assertJson(['ip' => '203.0.113.7']);
    }

    public function test_ignora_cabecalho_forjado_vindo_de_fora_da_rede_interna(): void
    {
        Route::get('/_teste_ip', fn (Request $r) => response()->json(['ip' => $r->ip()]));

        $this->como('1.2.3.4', '198.51.100.9')->getJson('/_teste_ip')->assertJson(['ip' => '198.51.100.9']);
    }

    public function test_login_tem_limite_por_visitante_e_nao_global(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->assertNotSame(429, $this->como('203.0.113.7')->postJson('/api/login', [])->getStatusCode(), "tentativa $i");
        }
        $this->como('203.0.113.7')->postJson('/api/login', [])->assertStatus(429);

        // outro visitante nao e afetado pelo limite do primeiro
        $this->assertNotSame(429, $this->como('203.0.113.8')->postJson('/api/login', [])->getStatusCode());
    }

    public function test_rotas_de_ia_tem_limite(): void
    {
        for ($i = 0; $i < 12; $i++) {
            $this->assertNotSame(429, $this->como('203.0.113.9')->postJson('/api/deepseek/image', [])->getStatusCode());
        }
        $this->como('203.0.113.9')->postJson('/api/deepseek/image', [])->assertStatus(429);
    }

    public function test_chat_do_widget_e_formularios_de_contato_tem_limite(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->assertNotSame(429, $this->como('203.0.113.10')->postJson('/api/bot/token-x/chat', [])->getStatusCode());
        }
        $this->como('203.0.113.10')->postJson('/api/bot/token-x/chat', [])->assertStatus(429);

        for ($i = 0; $i < 5; $i++) {
            $this->assertNotSame(429, $this->como('203.0.113.11')->postJson('/api/contato/enviar', [])->getStatusCode());
        }
        $this->como('203.0.113.11')->postJson('/api/contato/enviar', [])->assertStatus(429);
    }

    public static function webhooksTwilio(): array
    {
        return [['/api/webhook/twilio'], ['/api/webhook/sms'], ['/api/whatsapp/receive']];
    }

    /** @dataProvider webhooksTwilio */
    public function test_webhook_do_twilio_recusa_chamada_sem_assinatura_valida(string $url): void
    {
        config(['services.twilio.token' => 'token-secreto']);
        $dados = ['From' => 'whatsapp:+5521999999999', 'To' => 'whatsapp:+14155238886', 'Body' => 'oi'];

        $this->post($url, $dados)->assertForbidden();
        $this->post($url, $dados, ['X-Twilio-Signature' => 'assinatura-errada'])->assertForbidden();
    }

    public function test_webhook_do_twilio_aceita_assinatura_valida(): void
    {
        config(['services.twilio.token' => 'token-secreto']);
        $dados = ['From' => 'whatsapp:+5521999999999', 'Body' => 'oi'];

        foreach (['/api/whatsapp/receive', '/api/webhook/sms'] as $caminho) {
            $assinatura = (new RequestValidator('token-secreto'))->computeSignature('http://localhost' . $caminho, $dados);

            $status = $this->post($caminho, $dados, ['X-Twilio-Signature' => $assinatura])->getStatusCode();

            // passou da barreira da assinatura (o SMS grava em asaas_webhook_logs, tabela que nenhuma migration cria)
            $this->assertNotContains($status, [403, 503], $caminho);
        }

        $this->post('/api/whatsapp/receive', $dados, ['X-Twilio-Signature' => (new RequestValidator('token-secreto'))->computeSignature('http://localhost/api/whatsapp/receive', $dados)])->assertOk();
    }

    public function test_webhook_do_twilio_sem_token_configurado_nega(): void
    {
        config(['services.twilio.token' => null]);

        $this->post('/api/whatsapp/receive', ['Body' => 'oi'], ['X-Twilio-Signature' => 'x'])->assertStatus(503);
    }

    public function test_rotas_sem_controller_foram_removidas(): void
    {
        $this->post('/api/webhook/openai')->assertNotFound();
        $this->post('/api/webhook/deepseek')->assertNotFound();
    }
}
