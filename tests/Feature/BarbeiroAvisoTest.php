<?php

namespace Tests\Feature;

use App\Models\Alunos;
use App\Models\BarbeariaPushInscricao;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Usuario;
use App\Services\Barbearia\EnviadorWebPush;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** Aviso no celular do barbeiro (Web Push): inscrição do aparelho, envio no check-in, limpeza e isolamento. */
class BarbeiroAvisoTest extends TestCase
{
    use RefreshDatabase;

    private array $a;
    private array $b;
    private EnviadorFalso $falso;

    protected function setUp(): void
    {
        parent::setUp();

        $this->a = $this->barbearia('A');
        $this->b = $this->barbearia('B');

        // nenhum teste fala com o serviço de push de verdade
        $this->falso = new EnviadorFalso();
        $this->app->instance(EnviadorWebPush::class, $this->falso);
    }

    private function barbearia(string $k): array
    {
        $modalidade = Modalidade::where('nicho', 'barbearia')->first();
        $dono = Usuario::forceCreate(['nome' => "Dono $k", 'email' => "dono$k@teste.com", 'password' => Hash::make('1234'), 'tipo_usuario' => 'Professor']);
        $empresa = Empresa::forceCreate(['user_id' => $dono->id, 'modalidade_id' => $modalidade->id, 'nome' => "Barbearia $k", 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '', 'status' => 'ativo']);
        $user = Usuario::forceCreate(['nome' => "Barbeiro $k", 'email' => "barbeiro$k@teste.com", 'password' => Hash::make('1234'), 'tipo_usuario' => 'Professor']);
        $barbeiro = Professor::forceCreate(['usuario_id' => $user->id, 'empresa_id' => $empresa->id, 'modalidade_id' => $modalidade->id, 'sobre' => '', 'avatar' => '', 'ativo' => true]);

        return compact('dono', 'empresa', 'barbeiro', 'user');
    }

    private function cliente(string $nome = 'Cliente Zé', string $tel = '21988887777'): Usuario
    {
        $u = Usuario::forceCreate(['nome' => $nome, 'email' => "$tel@celular.invalid", 'password' => Hash::make('1234'), 'tipo_usuario' => 'Aluno', 'telefone' => $tel]);
        Alunos::create(['usuario_id' => $u->id]);

        return $u;
    }

    private function corpo(string $endpoint = 'https://push.exemplo.com/abc'): array
    {
        return ['endpoint' => $endpoint, 'keys' => ['p256dh' => 'chave-p256dh', 'auth' => 'chave-auth']];
    }

    private function inscrever(Usuario $u, string $endpoint = 'https://push.exemplo.com/abc'): void
    {
        $this->actingAs($u)->postJson(route('barbearia.painel.aviso'), $this->corpo($endpoint))->assertOk();
    }

    private function fazerCheckin(Usuario $cliente, array $b)
    {
        $b['empresa']->update(['checkin_exige_qr' => false]);

        $resposta = $this->actingAs($cliente)->post(route('barbearia.checkin', [$b['empresa'], $b['barbeiro']]));
        $this->limparAvisosPosResposta();

        return $resposta;
    }

    /**
     * Em produção cada request é um processo novo. O teste reaproveita a mesma aplicação, então as tarefas
     * "depois da resposta" do request anterior sobrariam e rodariam de novo no seguinte.
     */
    private function limparAvisosPosResposta(): void
    {
        $prop = new \ReflectionProperty($this->app, 'terminatingCallbacks');
        $prop->setAccessible(true);
        $prop->setValue($this->app, []);
    }

    // ── inscrição do aparelho ─────────────────────────────────────────────

    public function test_inscrever_exige_login(): void
    {
        $this->postJson(route('barbearia.painel.aviso'), $this->corpo())->assertUnauthorized();
        $this->assertSame(0, BarbeariaPushInscricao::count());
    }

    public function test_so_barbeiro_se_inscreve_cliente_e_dono_nao(): void
    {
        $this->actingAs($this->cliente())->postJson(route('barbearia.painel.aviso'), $this->corpo())->assertForbidden();
        $this->actingAs($this->a['dono'])->postJson(route('barbearia.painel.aviso'), $this->corpo())->assertForbidden();
        $this->assertSame(0, BarbeariaPushInscricao::count());
    }

    public function test_barbeiro_se_inscreve_e_o_mesmo_aparelho_nao_duplica(): void
    {
        $this->inscrever($this->a['user']);
        $this->inscrever($this->a['user']);

        $this->assertSame(1, BarbeariaPushInscricao::count());
        $this->assertSame($this->a['user']->id, BarbeariaPushInscricao::first()->usuario_id);
    }

    public function test_aparelho_que_troca_de_barbeiro_fica_com_o_ultimo(): void
    {
        $this->inscrever($this->a['user']);
        $this->inscrever($this->b['user']);

        $this->assertSame(1, BarbeariaPushInscricao::count());
        $this->assertSame($this->b['user']->id, BarbeariaPushInscricao::first()->usuario_id);
    }

    public function test_inscricao_invalida_e_recusada(): void
    {
        $this->actingAs($this->a['user'])->postJson(route('barbearia.painel.aviso'), ['endpoint' => 'nao-e-url', 'keys' => []])->assertStatus(422);
        $this->assertSame(0, BarbeariaPushInscricao::count());
    }

    public function test_desativar_remove_so_o_aparelho_do_proprio_barbeiro(): void
    {
        $this->inscrever($this->a['user'], 'https://push.exemplo.com/a');
        $this->inscrever($this->b['user'], 'https://push.exemplo.com/b');

        // B tenta remover o aparelho de A: nada acontece com A
        $this->actingAs($this->b['user'])->deleteJson(route('barbearia.painel.aviso.cancelar'), ['endpoint' => 'https://push.exemplo.com/a'])->assertOk();
        $this->assertSame(2, BarbeariaPushInscricao::count());

        $this->actingAs($this->a['user'])->deleteJson(route('barbearia.painel.aviso.cancelar'), ['endpoint' => 'https://push.exemplo.com/a'])->assertOk();
        $this->assertSame(1, BarbeariaPushInscricao::count());
        $this->assertSame($this->b['user']->id, BarbeariaPushInscricao::first()->usuario_id);
    }

    // ── envio no check-in ─────────────────────────────────────────────────

    public function test_checkin_avisa_o_celular_do_barbeiro_escolhido(): void
    {
        $this->inscrever($this->a['user']);
        $this->inscrever($this->b['user'], 'https://push.exemplo.com/b');

        $this->fazerCheckin($this->cliente(), $this->a)->assertRedirect();

        $this->assertCount(1, $this->falso->envios, 'um aviso, só para o barbeiro A');
        $envio = $this->falso->envios[0];
        $this->assertSame(['https://push.exemplo.com/abc'], array_column($envio['inscricoes'], 'endpoint'));
        $this->assertStringContainsString('Cliente Zé', $envio['conteudo']['texto']);
        $this->assertStringContainsString('1 pessoa esperando', $envio['conteudo']['texto']);
        $this->assertSame('/barbearia/painel/fila', $envio['conteudo']['url']);
    }

    public function test_aviso_conta_quantos_esperam(): void
    {
        $this->inscrever($this->a['user']);

        $this->fazerCheckin($this->cliente('Primeiro', '21911110001'), $this->a);
        $this->fazerCheckin($this->cliente('Segundo', '21911110002'), $this->a);

        $this->assertCount(2, $this->falso->envios);
        $this->assertStringContainsString('2 pessoas esperando', $this->falso->envios[1]['conteudo']['texto']);
    }

    public function test_repetir_o_checkin_nao_avisa_de_novo(): void
    {
        $this->inscrever($this->a['user']);
        $cliente = $this->cliente();

        $this->fazerCheckin($cliente, $this->a);
        $this->fazerCheckin($cliente, $this->a);

        $this->assertCount(1, $this->falso->envios);
    }

    public function test_barbeiro_sem_aparelho_inscrito_nao_dispara_envio_e_checkin_funciona(): void
    {
        $this->fazerCheckin($this->cliente(), $this->a)->assertRedirect();

        $this->assertCount(0, $this->falso->envios);
        $this->assertDatabaseCount('fila_atendimentos', 1);
    }

    public function test_falha_no_servico_de_push_nao_derruba_o_checkin(): void
    {
        $this->inscrever($this->a['user']);
        $this->falso->explodir = true;

        $this->fazerCheckin($this->cliente(), $this->a)->assertRedirect();

        $this->assertDatabaseCount('fila_atendimentos', 1);
    }

    public function test_aparelho_que_o_push_diz_nao_existir_mais_e_apagado(): void
    {
        $this->inscrever($this->a['user'], 'https://push.exemplo.com/morto');
        $this->inscrever($this->a['user'], 'https://push.exemplo.com/vivo');
        $this->falso->mortos = ['https://push.exemplo.com/morto'];

        $this->fazerCheckin($this->cliente(), $this->a);

        $this->assertSame(['https://push.exemplo.com/vivo'], BarbeariaPushInscricao::pluck('endpoint')->all());
    }

    public function test_sem_chaves_vapid_configuradas_nada_e_enviado(): void
    {
        $this->falso->configurado = false;
        $this->inscrever($this->a['user']);

        $this->fazerCheckin($this->cliente(), $this->a)->assertRedirect();

        $this->assertCount(0, $this->falso->envios);
    }

    public function test_botao_de_teste_envia_so_para_o_proprio_barbeiro(): void
    {
        $this->inscrever($this->a['user']);
        $this->inscrever($this->b['user'], 'https://push.exemplo.com/b');

        $this->actingAs($this->a['user'])->postJson(route('barbearia.painel.aviso.testar'))->assertOk()->assertJson(['enviados' => 1]);

        $this->assertSame(['https://push.exemplo.com/abc'], array_column($this->falso->envios[0]['inscricoes'], 'endpoint'));
    }

    public function test_cliente_nao_usa_o_botao_de_teste(): void
    {
        $this->actingAs($this->cliente())->postJson(route('barbearia.painel.aviso.testar'))->assertForbidden();
    }

    // ── service worker, manifesto e tela ──────────────────────────────────

    public function test_service_worker_e_publico_com_alcance_so_da_barbearia(): void
    {
        $r = $this->get('/barbearia/sw.js')->assertOk();

        $this->assertStringContainsString('application/javascript', $r->headers->get('Content-Type'));
        $this->assertSame('/barbearia/', $r->headers->get('Service-Worker-Allowed'));
        $this->assertStringContainsString('no-store', $r->headers->get('Cache-Control'));
        $this->assertStringContainsString("addEventListener('push'", $r->getContent());
    }

    public function test_manifesto_abre_na_fila_do_barbeiro(): void
    {
        $j = $this->get('/barbearia/manifest.webmanifest')->assertOk()->json();

        $this->assertSame('/barbearia/painel/fila', $j['start_url']);
        $this->assertSame('/barbearia/', $j['scope']);
        $this->assertSame('standalone', $j['display']);
        $this->assertFileExists(public_path('images/barbearia/icone-192.png'));
        $this->assertFileExists(public_path('images/barbearia/icone-512.png'));
    }

    public function test_tela_do_barbeiro_traz_o_painel_de_avisos_e_o_manifesto(): void
    {
        config(['webpush.public_key' => 'CHAVE_PUBLICA_TESTE']);

        $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila'))
            ->assertOk()
            ->assertSee('Avisos de cliente na fila')
            ->assertSee('data-chave="CHAVE_PUBLICA_TESTE"', false)
            ->assertSee('/barbearia/manifest.webmanifest', false)
            ->assertSee('id="ids-espera"', false);
    }

    public function test_atualizacao_da_fila_informa_os_ids_de_quem_espera(): void
    {
        $this->inscrever($this->a['user']);
        $this->fazerCheckin($this->cliente(), $this->a);
        $id = \App\Models\FilaAtendimento::first()->id;

        $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila', ['fragmento' => 1]))
            ->assertOk()->assertSee('data-ids="' . $id . '"', false);
    }
}

/** Troca o envio real: guarda o que seria enviado. */
class EnviadorFalso extends EnviadorWebPush
{
    public array $envios = [];
    public array $mortos = [];
    public bool $explodir = false;
    public bool $configurado = true;

    public function configurado(): bool
    {
        return $this->configurado;
    }

    public function enviar(array $inscricoes, array $conteudo): array
    {
        if ($this->explodir) {
            throw new \RuntimeException('serviço de push fora do ar');
        }

        $this->envios[] = ['inscricoes' => $inscricoes, 'conteudo' => $conteudo];

        return $this->mortos;
    }
}
