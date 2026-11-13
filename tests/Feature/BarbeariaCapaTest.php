<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Capa da barbearia: o dono envia a foto, ela aparece na lista e na página, e cada dono só mexe na sua. */
class BarbeariaCapaTest extends TestCase
{
    use RefreshDatabase;

    private array $a;
    private array $b;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->a = $this->barbearia('A');
        $this->b = $this->barbearia('B');
    }

    private function barbearia(string $k): array
    {
        $modalidade = Modalidade::where('nicho', 'barbearia')->first();
        $dono = Usuario::forceCreate(['nome' => "Dono $k", 'email' => "dono$k@teste.com", 'password' => Hash::make('1234'), 'tipo_usuario' => 'Professor']);
        $empresa = Empresa::forceCreate(['user_id' => $dono->id, 'modalidade_id' => $modalidade->id, 'nome' => "Barbearia $k", 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '', 'status' => 'ativo']);
        $user = Usuario::forceCreate(['nome' => "Barbeiro $k", 'email' => "barbeiro$k@teste.com", 'password' => Hash::make('1234'), 'tipo_usuario' => 'Professor']);
        Professor::forceCreate(['usuario_id' => $user->id, 'empresa_id' => $empresa->id, 'modalidade_id' => $modalidade->id, 'sobre' => '', 'avatar' => '', 'ativo' => true]);

        return compact('dono', 'empresa', 'user');
    }

    private function enviar(Usuario $quem, ?UploadedFile $arquivo = null, array $extra = [])
    {
        return $this->actingAs($quem)->post(route('barbearia.capa'), ['capa' => $arquivo ?? UploadedFile::fake()->image('capa.png', 1200, 500)] + $extra);
    }

    public function test_dono_envia_a_capa_e_ela_fica_guardada(): void
    {
        $this->enviar($this->a['dono'])->assertRedirect();

        $capa = $this->a['empresa']->fresh()->capa;
        $this->assertNotNull($capa);
        $this->assertStringStartsWith('barbearia/capas/', $capa);
        Storage::disk('public')->assertExists($capa);
    }

    public function test_envio_por_fetch_responde_json_com_o_endereco(): void
    {
        $r = $this->actingAs($this->a['dono'])->postJson(route('barbearia.capa'), ['capa' => UploadedFile::fake()->image('c.png', 800, 400)])->assertOk();

        $this->assertTrue($r->json('ok'));
        $this->assertStringContainsString('storage/' . $this->a['empresa']->fresh()->capa, $r->json('capa'));
    }

    public function test_trocar_a_capa_apaga_a_foto_antiga(): void
    {
        $this->enviar($this->a['dono']);
        $antiga = $this->a['empresa']->fresh()->capa;

        $this->enviar($this->a['dono']);
        $nova = $this->a['empresa']->fresh()->capa;

        $this->assertNotSame($antiga, $nova);
        Storage::disk('public')->assertMissing($antiga);
        Storage::disk('public')->assertExists($nova);
    }

    public function test_remover_apaga_o_arquivo_e_limpa_o_campo(): void
    {
        $this->enviar($this->a['dono']);
        $capa = $this->a['empresa']->fresh()->capa;

        $this->actingAs($this->a['dono'])->post(route('barbearia.capa'), ['remover' => 1])->assertRedirect();

        $this->assertNull($this->a['empresa']->fresh()->capa);
        Storage::disk('public')->assertMissing($capa);
    }

    public function test_arquivo_que_nao_e_imagem_ou_e_grande_demais_e_recusado(): void
    {
        $this->actingAs($this->a['dono'])->postJson(route('barbearia.capa'), ['capa' => UploadedFile::fake()->create('virus.php', 10, 'application/x-php')])->assertStatus(422);
        $this->actingAs($this->a['dono'])->postJson(route('barbearia.capa'), ['capa' => UploadedFile::fake()->create('grande.png', 7000, 'image/png')])->assertStatus(422);
        $this->actingAs($this->a['dono'])->postJson(route('barbearia.capa'), [])->assertStatus(422);

        $this->assertNull($this->a['empresa']->fresh()->capa);
    }

    public function test_so_o_dono_troca_a_capa_barbeiro_cliente_e_visitante_nao(): void
    {
        $this->post(route('barbearia.capa'), ['capa' => UploadedFile::fake()->image('c.png')])->assertRedirect(route('barbearia.login'));
        $this->enviar($this->a['user'])->assertForbidden();

        $cliente = Usuario::forceCreate(['nome' => 'Cli', 'email' => 'cli@celular.invalid', 'password' => 'x', 'tipo_usuario' => 'Aluno']);
        $this->enviar($cliente)->assertForbidden();

        $this->assertNull($this->a['empresa']->fresh()->capa);
    }

    public function test_dono_nao_troca_a_capa_da_barbearia_de_outro_dono(): void
    {
        // o dono A pede a empresa B: a capa vai para a DELE, a de B não muda
        $this->enviar($this->a['dono'], null, ['empresa' => $this->b['empresa']->id])->assertRedirect();

        $this->assertNotNull($this->a['empresa']->fresh()->capa);
        $this->assertNull($this->b['empresa']->fresh()->capa);
    }

    public function test_capa_aparece_na_lista_e_na_pagina_da_barbearia(): void
    {
        $this->enviar($this->a['dono']);
        $url = $this->a['empresa']->fresh()->capaUrl();

        $lista = $this->get('/barbearia')->assertOk()->getContent();
        $this->assertStringContainsString($url, $lista);
        $this->assertStringContainsString('capa vazia', $lista, 'a barbearia B, sem foto, mostra a listra de poste');

        $this->get(route('barbearia.show', $this->a['empresa']))->assertOk()->assertSee($url, false);
        $this->get(route('barbearia.show', $this->b['empresa']))->assertOk()->assertSee('capa vazia', false);
    }

    public function test_painel_do_dono_mostra_o_campo_da_capa(): void
    {
        $this->actingAs($this->a['dono'])->get(route('barbearia.equipe'))->assertOk()->assertSee('Capa da barbearia')->assertSee('Escolher foto');

        $this->enviar($this->a['dono']);
        $this->actingAs($this->a['dono'])->get(route('barbearia.equipe'))->assertOk()->assertSee('Trocar foto')->assertSee('Remover capa');
    }
}
