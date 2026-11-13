<?php

namespace Tests\Feature;

use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\FilaAtendimento;
use App\Models\Modalidade;
use App\Models\NichoConfiguracao;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** Barbearia: equipe do dono, cadastro/login do cliente pelo celular, check-in na fila e isolamento entre barbearias. */
class BarbeariaFilaTest extends TestCase
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
        $dono = Usuario::forceCreate(['nome' => "Dono $k", 'email' => "dono$k@teste.com", 'password' => Hash::make('1234'), 'tipo_usuario' => 'Professor', 'telefone' => '2190000000' . ($k === 'A' ? '1' : '2')]);
        $empresa = Empresa::forceCreate(['user_id' => $dono->id, 'modalidade_id' => $modalidade->id, 'nome' => "Barbearia $k", 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '', 'status' => 'ativo']);
        $barbeiroUser = Usuario::forceCreate(['nome' => "Barbeiro $k", 'email' => "barbeiro$k@teste.com", 'password' => Hash::make('1234'), 'tipo_usuario' => 'Professor']);
        $barbeiro = Professor::forceCreate(['usuario_id' => $barbeiroUser->id, 'empresa_id' => $empresa->id, 'modalidade_id' => $modalidade->id, 'sobre' => '', 'avatar' => '', 'ativo' => true]);

        return compact('dono', 'empresa', 'barbeiro', 'barbeiroUser');
    }

    private function cliente(string $nome = 'Cliente', string $tel = '21988887777'): Usuario
    {
        $u = Usuario::forceCreate(['nome' => $nome, 'email' => "$tel@celular.invalid", 'password' => Hash::make('1234'), 'tipo_usuario' => 'Aluno', 'telefone' => $tel]);
        Alunos::create(['usuario_id' => $u->id]);

        return $u;
    }

    private function noLocal(array $b): void
    {
        $this->get(route('barbearia.qr', [$b['empresa'], $b['empresa']->tokenCheckin()]))->assertRedirect(route('barbearia.show', $b['empresa']));
    }

    // ── páginas públicas ──────────────────────────────────────────────────

    public function test_lista_mostra_so_barbearias_de_nicho_com_fila(): void
    {
        $outra = Modalidade::forceCreate(['nome' => 'Pilates X', 'nicho' => 'pilates']);
        Empresa::forceCreate(['user_id' => $this->a['dono']->id, 'modalidade_id' => $outra->id, 'nome' => 'Studio Pilates', 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '', 'status' => 'ativo']);

        $this->get('/barbearia')->assertOk()->assertSee('Barbearia A')->assertSee('Barbearia B')->assertDontSee('Studio Pilates');
    }

    public function test_com_uma_unica_barbearia_a_lista_pula_direto_para_ela(): void
    {
        $this->b['empresa']->update(['status' => 'inativo']);

        $this->get('/barbearia')->assertRedirect(route('barbearia.show', $this->a['empresa']));
    }

    public function test_dominio_de_outro_nicho_nao_esconde_as_barbearias(): void
    {
        // ex: surfgestao.com.br (nicho surf, sem a função de fila) não pode mostrar a lista vazia
        app()->instance('currentNicho', new NichoConfiguracao(['nicho' => 'surf', 'nome' => 'Surf']));

        $this->get('/barbearia')->assertOk()->assertSee('Barbearia A')->assertSee('Barbearia B');
    }

    public function test_lista_tem_atalho_de_entrada_para_barbeiro_e_dono(): void
    {
        $this->get('/barbearia')->assertOk()->assertSee(route('barbearia.login'), false)->assertSee('Entre no seu painel');
    }

    public function test_cada_papel_tem_a_sua_area_visual(): void
    {
        // cliente: claro; barbeiro: painel escuro; dono: painel azul
        $this->get(route('barbearia.show', $this->a['empresa']))->assertOk()->assertSee('area-cliente', false)->assertSee('Passo 1 de 2')->assertDontSee('Painel do barbeiro');

        $this->actingAs($this->a['barbeiro']->usuario)->get(route('barbearia.painel.fila'))
            ->assertOk()->assertSee('area-barbeiro', false)->assertSee('Painel do barbeiro')->assertDontSee('Painel do dono');

        $this->actingAs($this->a['dono'])->get(route('barbearia.equipe'))
            ->assertOk()->assertSee('area-dono', false)->assertSee('Painel do dono')->assertDontSee('Painel do barbeiro');
    }

    public function test_pagina_da_barbearia_lista_barbeiros_ativos_com_a_palavra_do_nicho(): void
    {
        $inativo = Usuario::forceCreate(['nome' => 'Barbeiro Inativo', 'email' => 'i@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        Professor::forceCreate(['usuario_id' => $inativo->id, 'empresa_id' => $this->a['empresa']->id, 'sobre' => '', 'avatar' => '', 'ativo' => false]);

        $this->get(route('barbearia.show', $this->a['empresa']))
            ->assertOk()->assertSee('Barbeiro A')->assertDontSee('Barbeiro Inativo')->assertDontSee('Barbeiro B')
            ->assertSee('Escolha o barbeiro');
    }

    public function test_empresa_de_outro_nicho_ou_inativa_da_404(): void
    {
        $pilates = Modalidade::forceCreate(['nome' => 'Pilates X', 'nicho' => 'pilates']);
        $outra = Empresa::forceCreate(['user_id' => $this->a['dono']->id, 'modalidade_id' => $pilates->id, 'nome' => 'Studio', 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '', 'status' => 'ativo']);
        $this->get(route('barbearia.show', $outra))->assertNotFound();

        $this->a['empresa']->update(['status' => 'inativo']);
        $this->get(route('barbearia.show', $this->a['empresa']))->assertNotFound();
    }

    public function test_barbeiro_de_outra_barbearia_nao_abre_pela_url_errada(): void
    {
        $this->get(route('barbearia.barbeiro', [$this->a['empresa'], $this->b['barbeiro']]))->assertNotFound();
    }

    // ── cadastro rápido e login do cliente ───────────────────────────────

    public function test_cadastro_rapido_cria_usuario_e_aluno_e_ja_entra_logado(): void
    {
        $this->post(route('barbearia.cadastro'), ['nome' => 'João', 'telefone' => '(21) 98888-7777', 'senha' => '1234', 'voltar' => '/barbearia/' . $this->a['empresa']->id])
            ->assertRedirect('/barbearia/' . $this->a['empresa']->id);

        $u = Usuario::where('telefone', '21988887777')->first();
        $this->assertNotNull($u);
        $this->assertSame('Aluno', $u->tipo_usuario);
        $this->assertNotSame('1234', $u->password);
        $this->assertTrue(Alunos::where('usuario_id', $u->id)->exists());
        $this->assertAuthenticatedAs($u);
    }

    public function test_cadastro_recusa_celular_repetido_e_dados_invalidos(): void
    {
        $this->cliente('Já existe', '21988887777');

        $this->post(route('barbearia.cadastro'), ['nome' => 'Outro', 'telefone' => '21988887777', 'senha' => '1234'])->assertSessionHasErrors('telefone', null, 'cadastro');
        $this->post(route('barbearia.cadastro'), ['nome' => '', 'telefone' => '123', 'senha' => '12'])->assertSessionHasErrors(['nome', 'telefone', 'senha']);
        $this->assertSame(1, Usuario::where('telefone', '21988887777')->count());
    }

    public function test_cadastro_nao_redireciona_para_fora_da_barbearia(): void
    {
        $this->post(route('barbearia.cadastro'), ['nome' => 'João', 'telefone' => '21988887777', 'senha' => '1234', 'voltar' => 'https://site-malicioso.com'])
            ->assertRedirect('/barbearia');
    }

    public function test_login_pelo_celular_com_qualquer_formatacao(): void
    {
        $u = $this->cliente();

        $this->post(route('barbearia.entrar'), ['telefone' => '+55 (21) 98888-7777', 'senha' => '1234'])->assertRedirect();
        $this->assertAuthenticatedAs($u);
    }

    public function test_login_com_senha_errada_nao_entra(): void
    {
        $this->cliente();

        $this->post(route('barbearia.entrar'), ['telefone' => '21988887777', 'senha' => 'errada'])->assertSessionHasErrors('telefone', null, 'entrar');
        $this->assertGuest();
    }

    public function test_dono_e_barbeiro_vao_para_o_painel_ao_entrar(): void
    {
        $this->post(route('barbearia.entrar'), ['telefone' => '21900000001', 'senha' => '1234'])->assertRedirect(route('barbearia.painel'));
    }

    // ── check-in ─────────────────────────────────────────────────────────

    public function test_checkin_exige_login(): void
    {
        $this->noLocal($this->a);
        $this->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]))->assertRedirect();
        $this->assertSame(0, FilaAtendimento::count());
    }

    public function test_checkin_sem_ter_escaneado_o_qr_e_recusado(): void
    {
        $this->actingAs($this->cliente());

        $this->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]))->assertSessionHas('erro');
        $this->assertSame(0, FilaAtendimento::count());
    }

    public function test_qr_com_codigo_errado_da_404_e_nao_libera(): void
    {
        $this->a['empresa']->tokenCheckin();
        $this->get(route('barbearia.qr', [$this->a['empresa'], 'codigo-errado']))->assertNotFound();

        $this->actingAs($this->cliente())->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]))->assertSessionHas('erro');
        $this->assertSame(0, FilaAtendimento::count());
    }

    public function test_qr_de_uma_barbearia_nao_libera_a_outra(): void
    {
        $this->noLocal($this->a);

        $this->actingAs($this->cliente())->post(route('barbearia.checkin', [$this->b['empresa'], $this->b['barbeiro']]))->assertSessionHas('erro');
        $this->assertSame(0, FilaAtendimento::count());
    }

    public function test_checkin_com_qr_entra_na_fila_e_vincula_cliente_ao_barbeiro(): void
    {
        $u = $this->cliente();
        $this->noLocal($this->a);

        $r = $this->actingAs($u)->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]));

        $fila = FilaAtendimento::first();
        $r->assertRedirect(route('barbearia.fila', $fila));
        $this->assertSame('aguardando', $fila->status);
        $this->assertSame($this->a['barbeiro']->id, $fila->professor_id);
        $this->assertSame($this->a['empresa']->id, $fila->empresa_id);
        $this->assertTrue(AlunoProfessor::where('aluno_id', $fila->aluno_id)->where('professor_id', $this->a['barbeiro']->id)->exists());
    }

    public function test_dono_pode_desligar_a_exigencia_do_qr(): void
    {
        $this->a['empresa']->update(['checkin_exige_qr' => false]);

        $this->actingAs($this->cliente())->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]))->assertRedirect();
        $this->assertSame(1, FilaAtendimento::count());
    }

    public function test_cliente_so_entra_uma_vez_na_fila_da_barbearia(): void
    {
        $outro = Usuario::forceCreate(['nome' => 'Outro Barbeiro', 'email' => 'ob@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $barbeiro2 = Professor::forceCreate(['usuario_id' => $outro->id, 'empresa_id' => $this->a['empresa']->id, 'sobre' => '', 'avatar' => '', 'ativo' => true]);
        $this->noLocal($this->a);
        $this->actingAs($this->cliente());

        $this->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]));
        $this->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]));
        $this->post(route('barbearia.checkin', [$this->a['empresa'], $barbeiro2]))->assertSessionHas('erro');

        $this->assertSame(1, FilaAtendimento::count());
    }

    public function test_cliente_pode_ter_varios_barbeiros_em_dias_diferentes(): void
    {
        $outro = Usuario::forceCreate(['nome' => 'Outro Barbeiro', 'email' => 'ob@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $barbeiro2 = Professor::forceCreate(['usuario_id' => $outro->id, 'empresa_id' => $this->a['empresa']->id, 'sobre' => '', 'avatar' => '', 'ativo' => true]);
        $this->noLocal($this->a);
        $u = $this->cliente();
        $this->actingAs($u);

        $this->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]));
        FilaAtendimento::first()->update(['status' => 'concluido']);
        $this->post(route('barbearia.checkin', [$this->a['empresa'], $barbeiro2]));

        $aluno = Alunos::where('usuario_id', $u->id)->first();
        $this->assertCount(2, $aluno->professores);
    }

    public function test_barbeiro_desativado_nao_recebe_checkin(): void
    {
        $this->a['barbeiro']->update(['ativo' => false]);
        $this->noLocal($this->a);

        $this->actingAs($this->cliente())->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]))->assertNotFound();
    }

    public function test_barbeiro_e_dono_nao_entram_na_fila(): void
    {
        $this->noLocal($this->a);

        $this->actingAs($this->a['dono'])->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]))->assertSessionHas('erro');
        $this->assertSame(0, FilaAtendimento::count());
    }

    // ── posição na fila ──────────────────────────────────────────────────

    public function test_posicao_segue_a_ordem_de_chegada_e_por_barbeiro(): void
    {
        $b = $this->a['barbeiro'];
        $empresa = $this->a['empresa'];
        $mk = fn (string $tel, $barbeiro, string $hora, string $status = 'aguardando') => FilaAtendimento::create([
            'empresa_id' => $empresa->id, 'professor_id' => $barbeiro->id,
            'aluno_id' => Alunos::where('usuario_id', $this->cliente($tel, $tel)->id)->value('id'),
            'status' => $status, 'chegou_em' => now()->setTime(10, 0)->addMinutes((int) $hora),
        ]);

        $primeiro = $mk('21911110001', $b, '0');
        $segundo = $mk('21911110002', $b, '5');
        $mk('21911110003', $b, '2', 'desistiu');
        $outroBarbeiro = Professor::forceCreate(['usuario_id' => $this->cliente('X', '21911110009')->id, 'empresa_id' => $empresa->id, 'sobre' => '', 'avatar' => '', 'ativo' => true]);
        $deOutro = $mk('21911110004', $outroBarbeiro, '1');

        $this->assertSame(1, $primeiro->posicao());
        $this->assertSame(2, $segundo->posicao());
        $this->assertSame(1, $deOutro->posicao());

        $primeiro->update(['status' => 'atendendo']);
        $this->assertSame(0, $primeiro->fresh()->posicao());
        $this->assertSame(1, $segundo->fresh()->posicao());
    }

    public function test_tela_da_fila_so_abre_para_o_dono_da_vaga_e_mostra_a_posicao(): void
    {
        $this->noLocal($this->a);
        $u = $this->cliente();
        $this->actingAs($u)->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]));
        $fila = FilaAtendimento::first();

        $this->get(route('barbearia.fila', $fila))->assertOk()->assertSee('1º')->assertSee('Você é o próximo');

        $intruso = $this->cliente('Intruso', '21977776666');
        $this->actingAs($intruso)->get(route('barbearia.fila', $fila))->assertForbidden();
        $this->actingAs($intruso)->post(route('barbearia.fila.desistir', $fila))->assertForbidden();
        $this->assertSame('aguardando', $fila->fresh()->status);
    }

    public function test_cliente_pode_desistir_da_fila(): void
    {
        $this->noLocal($this->a);
        $this->actingAs($this->cliente())->post(route('barbearia.checkin', [$this->a['empresa'], $this->a['barbeiro']]));
        $fila = FilaAtendimento::first();

        $this->post(route('barbearia.fila.desistir', $fila))->assertRedirect(route('barbearia.fila', $fila));

        $this->assertSame('desistiu', $fila->fresh()->status);
        $this->assertNotNull($fila->fresh()->finalizado_em);
    }

    // ── equipe do dono ───────────────────────────────────────────────────

    public function test_dono_cadastra_barbeiro_com_login_proprio(): void
    {
        $this->actingAs($this->a['dono'])->post(route('barbearia.equipe.store'), ['nome' => 'Novo Barbeiro', 'telefone' => '(21) 97777-6666', 'senha' => 'abcd'])
            ->assertRedirect();

        $u = Usuario::where('telefone', '21977776666')->first();
        $this->assertSame('Professor', $u->tipo_usuario);
        $p = Professor::where('usuario_id', $u->id)->first();
        $this->assertSame($this->a['empresa']->id, $p->empresa_id);
        $this->assertTrue((bool) $p->ativo);

        auth()->logout();
        $this->post(route('barbearia.entrar'), ['telefone' => '21977776666', 'senha' => 'abcd'])->assertRedirect(route('barbearia.painel'));
        $this->assertAuthenticatedAs($u);
    }

    public function test_dono_nao_cadastra_celular_repetido(): void
    {
        $this->actingAs($this->a['dono'])->post(route('barbearia.equipe.store'), ['nome' => 'Dup', 'telefone' => '21900000001', 'senha' => 'abcd'])
            ->assertSessionHasErrors('telefone');
    }

    public function test_dono_ativa_e_desativa_barbeiro_so_da_propria_barbearia(): void
    {
        $this->actingAs($this->a['dono']);

        $this->post(route('barbearia.equipe.ativo', $this->a['barbeiro']))->assertRedirect();
        $this->assertFalse((bool) $this->a['barbeiro']->fresh()->ativo);

        $this->post(route('barbearia.equipe.ativo', $this->b['barbeiro']))->assertForbidden();
        $this->assertTrue((bool) $this->b['barbeiro']->fresh()->ativo);
    }

    public function test_cliente_comum_nao_acessa_a_equipe(): void
    {
        $this->actingAs($this->cliente())->get(route('barbearia.equipe'))->assertForbidden();
        $this->post(route('barbearia.equipe.store'), ['nome' => 'X', 'telefone' => '21955554444', 'senha' => 'abcd'])->assertForbidden();
    }

    public function test_dono_de_uma_barbearia_nao_gerencia_a_outra_pelo_parametro_empresa(): void
    {
        $this->actingAs($this->a['dono'])->post(route('barbearia.equipe.store'), ['empresa' => $this->b['empresa']->id, 'nome' => 'Infiltrado', 'telefone' => '21955554444', 'senha' => 'abcd']);

        $this->assertSame(0, Professor::where('empresa_id', $this->b['empresa']->id)->whereHas('usuario', fn ($q) => $q->where('nome', 'Infiltrado'))->count());
        $this->assertSame(1, Professor::where('empresa_id', $this->a['empresa']->id)->whereHas('usuario', fn ($q) => $q->where('nome', 'Infiltrado'))->count());
    }

    public function test_tela_da_equipe_mostra_barbeiros_e_o_link_do_qr(): void
    {
        $r = $this->actingAs($this->a['dono'])->get(route('barbearia.equipe'));

        $r->assertOk()->assertSee('Barbeiro A')->assertDontSee('Barbeiro B')->assertSee('QR code de check-in');
        $r->assertSee('/barbearia/' . $this->a['empresa']->id . '/c/' . $this->a['empresa']->fresh()->checkin_token, false);
    }

    public function test_renovar_qr_invalida_o_antigo(): void
    {
        $antigo = $this->a['empresa']->tokenCheckin();

        $this->actingAs($this->a['dono'])->post(route('barbearia.checkin.config'), ['exige_qr' => 1, 'renovar' => 1])->assertRedirect();

        $this->assertNotSame($antigo, $this->a['empresa']->fresh()->checkin_token);
        auth()->logout();
        $this->get(route('barbearia.qr', [$this->a['empresa'], $antigo]))->assertNotFound();
    }

    public function test_barbeiro_cai_na_fila_dele_e_nao_acessa_a_equipe(): void
    {
        $this->actingAs($this->a['barbeiroUser'])->get(route('barbearia.painel'))->assertRedirect(route('barbearia.painel.fila'));
        $this->get(route('barbearia.equipe'))->assertForbidden();
    }
}
