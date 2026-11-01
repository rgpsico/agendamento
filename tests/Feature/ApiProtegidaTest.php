<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Escritas administrativas da API exigem login (sessao do painel ou token). Antes qualquer pessoa na
 * internet apagava/alterava empresas, usuarios, servicos, permissoes etc. sem se identificar.
 */
class ApiProtegidaTest extends TestCase
{
    use RefreshDatabase;

    public static function rotasProtegidas(): array
    {
        $l = [
            ['DELETE', '/api/servicos/1'], ['PUT', '/api/servicos/1'], ['POST', '/api/servicos'],
            ['DELETE', '/api/users/1'], ['PUT', '/api/users/1'], ['GET', '/api/users'], ['GET', '/api/users/1'],
            ['DELETE', '/api/empresas/1'], ['PUT', '/api/empresas/1'], ['POST', '/api/empresas'],
            ['DELETE', '/api/professor/1'], ['PUT', '/api/professor/1'], ['POST', '/api/professor'],
            ['DELETE', '/api/aulas/1'], ['POST', '/api/aulas'], ['DELETE', '/api/dias/1'], ['POST', '/api/dias'],
            ['DELETE', '/api/disponibilidade/1'], ['POST', '/api/disponibilidade'],
            ['DELETE', '/api/gallery/1'], ['DELETE', '/api/modalidade/1/destroy'], ['POST', '/api/modalidade/1/update'],
            ['POST', '/api/permissions/store'], ['PUT', '/api/permissions/update/1'], ['POST', '/api/permissions/delete/1'],
            ['POST', '/api/google-ads/create-campaign'], ['POST', '/api/admin/asaas/limpar-sandbox'],
            ['POST', '/api/pix/create-key'], ['DELETE', '/api/pix/keys/1'], ['POST', '/api/pix-simulate'],
            ['POST', '/api/send-whatsapp'], ['POST', '/api/email/teste'], ['POST', '/api/push/test'],
            ['DELETE', '/api/aluno/1/destroy/1'], ['POST', '/api/aluno/1/update'],
            ['PUT', '/api/agendamento/1'], ['DELETE', '/api/frontend/agendamentos/1'], ['PUT', '/api/frontend/disponibilidades/1'],
            ['DELETE', '/api/planos-alunos/1'], ['POST', '/api/planos-alunos'], ['POST', '/api/empresa/pagamento/store'],
            ['POST', '/api/test-twilio'],
        ];

        return array_combine(array_map(fn ($x) => "$x[0] $x[1]", $l), $l);
    }

    /** @dataProvider rotasProtegidas */
    public function test_visitante_recebe_401(string $metodo, string $url): void
    {
        $this->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])->json($metodo, $url)->assertUnauthorized();
    }

    public function test_usuario_logado_so_escreve_por_ajax_ou_json(): void
    {
        $u = Usuario::forceCreate(['nome' => 'A', 'email' => 'a@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);

        // formulario comum de outro site (sem X-Requested-With e sem JSON) e recusado
        $this->actingAs($u)->delete('/api/servicos/999999')->assertForbidden();

        // chamada AJAX do painel passa pela protecao e chega ao controller
        $status = $this->actingAs($u)->delete('/api/servicos/999999', [], ['X-Requested-With' => 'XMLHttpRequest'])->getStatusCode();
        $this->assertNotContains($status, [401, 403, 419]);
    }

    public function test_token_sanctum_tambem_autentica(): void
    {
        Sanctum::actingAs(Usuario::forceCreate(['nome' => 'B', 'email' => 'b@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']));

        $status = $this->deleteJson('/api/servicos/999999')->getStatusCode();
        $this->assertNotContains($status, [401, 403]);
    }

    public function test_leituras_e_fluxos_publicos_continuam_abertos(): void
    {
        $this->getJson('/api/servicos')->assertOk();
        $this->getJson('/api/empresas')->assertOk();

        // cadastro, login e agendamento publico nao podem pedir login (devem chegar ao controller)
        foreach (['/api/users', '/api/agendamento', '/api/clique-whatsapp', '/api/visualizacao', '/api/contato/enviar'] as $url) {
            $status = $this->postJson($url, [])->getStatusCode();
            $this->assertNotContains($status, [401, 403, 419], "$url nao deveria exigir login (status $status)");
        }
    }
}
