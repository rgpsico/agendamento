<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\FilaAtendimento;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** Tela do barbeiro: fila ao vivo (chamar, iniciar, finalizar, não apareceu) e agenda de hoje. */
class BarbeiroFilaTest extends TestCase
{
    use RefreshDatabase;

    private array $a;
    private array $b;

    protected function setUp(): void
    {
        parent::setUp();

        $this->a = $this->barbearia('A');
        $this->b = $this->barbearia('B');
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

    private function entrarNaFila(string $nome, array $b, string $hora = '10:00', string $status = 'aguardando'): FilaAtendimento
    {
        $u = Usuario::forceCreate(['nome' => $nome, 'email' => strtolower($nome) . '@celular.invalid', 'password' => 'x', 'tipo_usuario' => 'Aluno', 'telefone' => (string) random_int(21900000000, 21999999999)]);
        $aluno = Alunos::create(['usuario_id' => $u->id]);

        return FilaAtendimento::create([
            'empresa_id' => $b['empresa']->id, 'professor_id' => $b['barbeiro']->id, 'aluno_id' => $aluno->id,
            'status' => $status, 'chegou_em' => now()->setTimeFromTimeString($hora),
        ]);
    }

    private function agendar(array $b, Alunos $aluno, string $horario, ?string $data = null, string $status = 'Espera'): Agendamento
    {
        $servico = Servicos::firstOrCreate(['empresa_id' => $b['empresa']->id, 'titulo' => 'Corte'], ['imagem' => '', 'preco' => 40, 'tempo_de_aula' => 30]);

        $ag = Agendamento::forceCreate(['aluno_id' => $aluno->id, 'professor_id' => $b['barbeiro']->id, 'servico_id' => $servico->id,
            'data_da_aula' => $data ?? now()->toDateString(), 'horario' => $horario, 'valor_aula' => 40]);
        $ag->forceFill(['status' => $status])->save();

        return $ag;
    }

    // ── acesso ───────────────────────────────────────────────────────────

    public function test_painel_sem_login_manda_para_a_entrada_da_barbearia_e_nao_para_o_login_geral(): void
    {
        $this->get(route('barbearia.painel'))->assertRedirect(route('barbearia.login'));
        $this->get(route('barbearia.painel.fila'))->assertRedirect(route('barbearia.login'));
    }

    public function test_pagina_de_entrada_direta_e_o_barbeiro_cai_na_propria_fila(): void
    {
        $this->get(route('barbearia.login'))->assertOk()->assertSee('Celular')->assertSee('Senha');

        $this->a['user']->forceFill(['telefone' => '21990001111'])->save();
        $this->post(route('barbearia.entrar'), ['telefone' => '(21) 99000-1111', 'senha' => '1234'])->assertRedirect(route('barbearia.painel'));
        $this->get(route('barbearia.painel'))->assertRedirect(route('barbearia.painel.fila'));

        // já logado, a página de entrada leva direto para o painel
        $this->get(route('barbearia.login'))->assertRedirect(route('barbearia.painel'));
    }

    public function test_senha_errada_volta_para_a_entrada_com_aviso(): void
    {
        $this->a['user']->forceFill(['telefone' => '21990001111'])->save();

        $this->from(route('barbearia.login'))->post(route('barbearia.entrar'), ['telefone' => '21990001111', 'senha' => 'errada'])
            ->assertRedirect(route('barbearia.login'))->assertSessionHasErrors('telefone', null, 'entrar');
    }

    public function test_so_barbeiro_ativo_de_barbearia_abre_a_fila(): void
    {
        $this->get(route('barbearia.painel.fila'))->assertRedirect();

        $cliente = Usuario::forceCreate(['nome' => 'Cli', 'email' => 'c@c.invalid', 'password' => 'x', 'tipo_usuario' => 'Aluno']);
        $this->actingAs($cliente)->get(route('barbearia.painel.fila'))->assertForbidden();
        $this->actingAs($this->a['dono'])->get(route('barbearia.painel.fila'))->assertForbidden();

        $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila'))->assertOk()->assertSee('Olá, Barbeiro A');

        $this->a['barbeiro']->update(['ativo' => false]);
        $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila'))->assertForbidden();
    }

    public function test_dono_que_tambem_e_barbeiro_ve_o_atalho_da_propria_fila(): void
    {
        $this->actingAs($this->a['dono'])->get(route('barbearia.equipe'))->assertDontSee('Minha fila');

        Professor::forceCreate(['usuario_id' => $this->a['dono']->id, 'empresa_id' => $this->a['empresa']->id, 'sobre' => '', 'avatar' => '', 'ativo' => true]);

        $this->get(route('barbearia.equipe'))->assertSee('Minha fila');
        $this->get(route('barbearia.painel.fila'))->assertOk();
    }

    // ── fila e agenda na tela ────────────────────────────────────────────

    public function test_tela_mostra_a_fila_por_ordem_de_chegada_so_do_proprio_barbeiro(): void
    {
        $this->entrarNaFila('Segundo', $this->a, '10:20');
        $this->entrarNaFila('Primeiro', $this->a, '10:05');
        $this->entrarNaFila('Desistente', $this->a, '10:00', 'desistiu');
        $this->entrarNaFila('DeOutraBarbearia', $this->b, '10:01');

        $r = $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila'));

        $r->assertOk()->assertSeeInOrder(['Primeiro', 'Segundo'])->assertDontSee('Desistente')->assertDontSee('DeOutraBarbearia');
    }

    public function test_agenda_de_hoje_mostra_horario_servico_e_quem_ja_chegou(): void
    {
        $naFila = $this->entrarNaFila('Marcado', $this->a);
        $this->agendar($this->a, $naFila->aluno, '14:30:00');
        $ausente = $this->entrarNaFila('Ainda Nao Chegou', $this->a, '09:00', 'desistiu')->aluno;
        $this->agendar($this->a, $ausente, '16:00:00');

        $this->agendar($this->a, $naFila->aluno, '08:00:00', now()->subDay()->toDateString());
        $this->agendar($this->a, $naFila->aluno, '11:11:00', null, 'Cancelado');
        $this->agendar($this->b, $this->entrarNaFila('OutraCasa', $this->b)->aluno, '12:34:00');

        $r = $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila'));

        $r->assertSee('14:30')->assertSee('16:00')->assertSee('Corte')
            ->assertSee('tem horário marcado às 14:30')->assertSee('chegou')
            ->assertDontSee('08:00')->assertDontSee('11:11')->assertDontSee('12:34');
    }

    public function test_fragmento_para_atualizar_sozinho_vem_sem_o_layout(): void
    {
        $this->entrarNaFila('Cliente Um', $this->a);

        $r = $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila', ['fragmento' => 1]));

        $r->assertOk()->assertSee('Cliente Um')->assertDontSee('<html', false);
    }

    // ── ações ────────────────────────────────────────────────────────────

    public function test_chamar_avisa_o_cliente_na_tela_da_fila_dele(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);
        $cliente = $f->aluno->usuario;

        $this->actingAs($cliente)->get(route('barbearia.fila', $f))->assertDontSee('É a sua vez');

        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.chamar', $f))->assertRedirect(route('barbearia.painel.fila'));

        $this->assertNotNull($f->fresh()->chamado_em);
        $this->actingAs($cliente)->get(route('barbearia.fila', $f))->assertSee('É a sua vez');
        $this->get(route('barbearia.fila', ['fila' => $f, 'fragmento' => 1]))->assertSee('É a sua vez')->assertDontSee('<html', false);
    }

    public function test_iniciar_e_finalizar_um_atendimento(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);
        $this->actingAs($this->a['user']);

        $this->post(route('barbearia.painel.fila.iniciar', $f));
        $f->refresh();
        $this->assertSame('atendendo', $f->status);
        $this->assertNotNull($f->iniciado_em);
        $this->assertNotNull($f->chamado_em);

        $this->get(route('barbearia.painel.fila'))->assertSee('Em atendimento')->assertSee('Finalizar');

        $this->post(route('barbearia.painel.fila.finalizar', $f));
        $f->refresh();
        $this->assertSame('concluido', $f->status);
        $this->assertNotNull($f->finalizado_em);

        $this->get(route('barbearia.painel.fila'))->assertSee('atendidos hoje');
        $this->actingAs($f->aluno->usuario)->get(route('barbearia.fila', $f))->assertSee('Atendimento concluído');
    }

    public function test_nao_inicia_outro_enquanto_tem_um_em_atendimento(): void
    {
        $um = $this->entrarNaFila('Um', $this->a, '10:00');
        $dois = $this->entrarNaFila('Dois', $this->a, '10:05');
        $this->actingAs($this->a['user']);

        $this->post(route('barbearia.painel.fila.iniciar', $um));
        $this->post(route('barbearia.painel.fila.iniciar', $dois))->assertSessionHas('erro');

        $this->assertSame('aguardando', $dois->fresh()->status);

        $this->post(route('barbearia.painel.fila.finalizar', $um));
        $this->post(route('barbearia.painel.fila.iniciar', $dois))->assertSessionHas('ok');
        $this->assertSame('atendendo', $dois->fresh()->status);
    }

    public function test_cliente_que_nao_apareceu_sai_da_fila_e_ve_o_aviso(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);

        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.ausente', $f));

        $f->refresh();
        $this->assertSame('ausente', $f->status);
        $this->assertNotNull($f->finalizado_em);
        $this->assertFalse($f->aberta());
        $this->actingAs($f->aluno->usuario)->get(route('barbearia.fila', $f))->assertSee('retirado da fila');
    }

    public function test_acoes_em_status_errado_dao_erro_e_nao_mudam_nada(): void
    {
        $espera = $this->entrarNaFila('Espera', $this->a, '10:00');
        $atendendo = $this->entrarNaFila('Atendendo', $this->a, '10:05', 'atendendo');
        $this->actingAs($this->a['user']);

        $this->post(route('barbearia.painel.fila.finalizar', $espera))->assertSessionHas('erro');
        $this->post(route('barbearia.painel.fila.chamar', $atendendo))->assertSessionHas('erro');
        $this->post(route('barbearia.painel.fila.ausente', $atendendo))->assertSessionHas('erro');

        $this->assertSame('aguardando', $espera->fresh()->status);
        $this->assertSame('atendendo', $atendendo->fresh()->status);
    }

    public function test_barbeiro_nao_mexe_na_fila_de_outro_barbeiro_nem_de_outra_barbearia(): void
    {
        $outroDaMesmaCasaUser = Usuario::forceCreate(['nome' => 'Colega', 'email' => 'colega@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $colega = Professor::forceCreate(['usuario_id' => $outroDaMesmaCasaUser->id, 'empresa_id' => $this->a['empresa']->id, 'sobre' => '', 'avatar' => '', 'ativo' => true]);
        $doColega = $this->entrarNaFila('DoColega', ['empresa' => $this->a['empresa'], 'barbeiro' => $colega]);
        $deOutraCasa = $this->entrarNaFila('DeB', $this->b);

        $this->actingAs($this->a['user']);
        foreach (['chamar', 'iniciar', 'finalizar', 'ausente'] as $acao) {
            $this->post(route("barbearia.painel.fila.$acao", $doColega))->assertForbidden();
            $this->post(route("barbearia.painel.fila.$acao", $deOutraCasa))->assertForbidden();
        }

        $this->assertSame('aguardando', $doColega->fresh()->status);
        $this->assertSame('aguardando', $deOutraCasa->fresh()->status);
    }

    public function test_cliente_nao_consegue_chamar_nem_iniciar(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);

        $this->actingAs($f->aluno->usuario)->post(route('barbearia.painel.fila.iniciar', $f))->assertForbidden();
        $this->assertSame('aguardando', $f->fresh()->status);
    }

    public function test_fila_concluida_mostra_voltar_e_nao_oferece_sair(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a, '10:00', 'concluido');

        $this->actingAs($f->aluno->usuario)->get(route('barbearia.fila', $f))->assertSee('Voltar para a barbearia')->assertDontSee('Sair da fila');
    }

    // ── recado "onde você está?" ──────────────────────────────────────────

    public function test_barbeiro_manda_recado_e_o_cliente_ve_na_tela_da_fila(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);
        $cliente = $f->aluno->usuario;

        $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila'))->assertSee('Perguntar onde está');
        $this->actingAs($cliente)->get(route('barbearia.fila', $f))->assertDontSee('perguntou');

        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'Cadê você, já é o próximo?'])
            ->assertRedirect(route('barbearia.painel.fila'))->assertSessionHas('ok');

        $this->assertSame('Cadê você, já é o próximo?', $f->fresh()->mensagem);
        $this->actingAs($cliente)->get(route('barbearia.fila', $f))->assertSee('perguntou')->assertSee('Cadê você, já é o próximo?')->assertSee('Estou na barbearia');
        $this->get(route('barbearia.fila', ['fila' => $f, 'fragmento' => 1]))->assertSee('Cadê você')->assertDontSee('<html', false);
    }

    public function test_recado_em_branco_usa_a_pergunta_padrao(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);

        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => '  '])->assertSessionHas('ok');

        $this->assertStringContainsString('Onde você está', $f->fresh()->mensagem);
    }

    public function test_recado_muito_longo_e_recusado(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);

        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => str_repeat('a', 141)])->assertSessionHasErrors('texto');
        $this->assertNull($f->fresh()->mensagem);
    }

    public function test_recado_e_escapado_na_tela_do_cliente(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);
        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => '<script>alert(1)</script>']);

        $this->actingAs($f->aluno->usuario)->get(route('barbearia.fila', $f))->assertDontSee('<script>alert(1)</script>', false)->assertSee('&lt;script&gt;', false);
    }

    public function test_cliente_responde_e_o_barbeiro_ve_a_resposta(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);
        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'Onde você está?']);

        $this->actingAs($f->aluno->usuario)->post(route('barbearia.fila.responder', $f), ['resposta' => 'chegando'])->assertRedirect(route('barbearia.fila', $f));
        $this->assertSame('Estou chegando', $f->fresh()->resposta);

        $this->actingAs($this->a['user'])->get(route('barbearia.painel.fila'))->assertSee('Você perguntou')->assertSee('Estou chegando');
        $this->actingAs($f->aluno->usuario)->get(route('barbearia.fila', $f))->assertSee('Você respondeu:');
    }

    public function test_nova_pergunta_limpa_a_resposta_antiga(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);
        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'um']);
        $this->actingAs($f->aluno->usuario)->post(route('barbearia.fila.responder', $f), ['resposta' => 'aqui']);
        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'dois']);

        $this->assertNull($f->fresh()->resposta);
        $this->assertSame('dois', $f->fresh()->mensagem);
    }

    public function test_resposta_invalida_ou_sem_pergunta_nao_grava_nada(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);
        $cliente = $f->aluno->usuario;

        $this->actingAs($cliente)->post(route('barbearia.fila.responder', $f), ['resposta' => 'aqui']);
        $this->assertNull($f->fresh()->resposta); // ninguém perguntou

        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'x']);
        $this->actingAs($cliente)->post(route('barbearia.fila.responder', $f), ['resposta' => 'qualquer coisa'])->assertSessionHasErrors('resposta');
        $this->assertNull($f->fresh()->resposta);
    }

    public function test_so_o_barbeiro_da_fila_manda_recado_e_so_o_dono_da_vaga_responde(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a);

        $this->actingAs($this->b['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'oi'])->assertForbidden();
        $this->actingAs($f->aluno->usuario)->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'oi'])->assertForbidden();

        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'oi']);
        $outro = $this->entrarNaFila('Outro', $this->a)->aluno->usuario;
        $this->actingAs($outro)->post(route('barbearia.fila.responder', $f), ['resposta' => 'aqui'])->assertForbidden();
        $this->assertNull($f->fresh()->resposta);
    }

    public function test_recado_para_quem_nao_esta_mais_esperando_da_erro(): void
    {
        $f = $this->entrarNaFila('Cliente', $this->a, '10:00', 'atendendo');

        $this->actingAs($this->a['user'])->post(route('barbearia.painel.fila.mensagem', $f), ['texto' => 'oi'])->assertSessionHas('erro');
        $this->assertNull($f->fresh()->mensagem);
    }
}
