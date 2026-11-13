<?php

namespace Tests\Feature;

use App\Models\Modalidade;
use App\Models\NichoConfiguracao;
use App\Models\Usuario;
use App\Support\Nicho;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

/** Configuração por nicho: palavras e funções próprias (barbearia) sem mexer nos nichos antigos. */
class NichoTermosRecursosTest extends TestCase
{
    use RefreshDatabase;

    public function test_nicho_barbearia_nasce_com_barbeiro_cliente_e_fila_de_checkin(): void
    {
        $nicho = NichoConfiguracao::where('nicho', 'barbearia')->first();

        $this->assertNotNull($nicho);
        $this->assertSame('Barbeiro', $nicho->termo('profissional'));
        $this->assertSame('Barbeiros', $nicho->termo('profissionais'));
        $this->assertTrue($nicho->recurso('fila_checkin'));
        $this->assertNull($nicho->dominio, 'sem domínio até o dono do sistema definir');
        $this->assertTrue(Modalidade::doNicho('barbearia')->where('nome', 'Barbearia')->exists());
    }

    public function test_nicho_sem_configuracao_mantem_as_palavras_de_sempre_e_sem_funcoes_extras(): void
    {
        $pilates = NichoConfiguracao::create(['nicho' => 'pilates-teste', 'nome' => 'P', 'cor_primaria' => '#000', 'cor_secundaria' => '#fff']);

        $this->assertSame('Professor', $pilates->termo('profissional'));
        $this->assertSame('Alunos', $pilates->termo('clientes'));
        $this->assertFalse($pilates->recurso('fila_checkin'));

        // sem nicho no domínio
        $this->assertSame('Professor', Nicho::termo('profissional'));
        $this->assertFalse(Nicho::recurso('fila_checkin'));
    }

    public function test_blade_usa_a_palavra_do_nicho_do_dominio(): void
    {
        app()->instance('currentNicho', NichoConfiguracao::where('nicho', 'barbearia')->first());

        $html = Blade::render("@termo('profissionais') / @recurso('fila_checkin') fila @endrecurso");

        $this->assertSame('Barbeiros /  fila ', $html);
    }

    public function test_super_admin_salva_palavras_e_funcoes_ignorando_o_que_o_sistema_nao_conhece(): void
    {
        config(['master_users.emails' => ['master@teste.com']]);
        $master = Usuario::forceCreate(['nome' => 'Master', 'email' => 'master@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $nicho = NichoConfiguracao::where('nicho', 'barbearia')->first();

        $this->actingAs($master)->put(route('super.admin.nichos.update', $nicho), [
            'nome' => 'Barbearia Gestão', 'cor_primaria' => '#111111', 'cor_secundaria' => '#c9a24b', 'ativo' => 1,
            'termos' => ['profissional' => 'Barbeiro', 'profissionais' => '', 'cliente' => 'Freguês', 'invasor' => 'x'],
            'recursos' => ['fila_checkin' => 1, 'invasor' => 1],
        ])->assertRedirect(route('super.admin.nichos'));

        $nicho->refresh();
        $this->assertEquals(['profissional' => 'Barbeiro', 'cliente' => 'Freguês'], $nicho->termos);
        $this->assertSame('Barbeiros', $nicho->termo('profissionais', 'Barbeiros'));
        $this->assertSame(['fila_checkin' => true], $nicho->recursos);

        // desmarcar tudo desliga a função
        $this->actingAs($master)->put(route('super.admin.nichos.update', $nicho), [
            'nome' => 'Barbearia Gestão', 'cor_primaria' => '#111111', 'cor_secundaria' => '#c9a24b', 'ativo' => 1,
        ]);
        $this->assertFalse($nicho->fresh()->recurso('fila_checkin'));
    }

    public function test_tela_de_edicao_mostra_os_campos(): void
    {
        config(['master_users.emails' => ['master@teste.com']]);
        $master = Usuario::forceCreate(['nome' => 'Master', 'email' => 'master@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $nicho = NichoConfiguracao::where('nicho', 'barbearia')->first();

        $this->actingAs($master)->get(route('super.admin.nichos.edit', $nicho))
            ->assertOk()->assertSee('Palavras do nicho')->assertSee('Funções do nicho')->assertSee('value="Barbeiro"', false);
    }
}
