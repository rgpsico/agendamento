<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\EmpresaEndereco;
use App\Models\EmpresaSite;
use App\Models\FilaAtendimento;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\SiteContato;
use App\Models\SiteDepoimento;
use App\Models\SiteServico;
use App\Models\SiteTemplate;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Site público da barbearia (template site.barbearia): só dados reais, fila ao vivo e entrada para o check-in. */
class BarbeariaSiteTest extends TestCase
{
    use RefreshDatabase;

    private const HOST = 'barbearia-teste.com.br';

    private function barbearia(string $nicho = 'barbearia', string $host = self::HOST, string $nome = 'Barbearia do Zé'): array
    {
        $modalidade = Modalidade::where('nicho', $nicho)->first() ?? Modalidade::forceCreate(['nome' => $nicho, 'nicho' => $nicho]);
        $dono = Usuario::forceCreate(['nome' => "Dono $host", 'email' => "dono@$host", 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $empresa = Empresa::forceCreate(['user_id' => $dono->id, 'modalidade_id' => $modalidade->id, 'nome' => $nome, 'descricao' => 'd', 'telefone' => '(21) 3333-4444', 'cnpj' => '', 'status' => 'ativo']);
        $site = EmpresaSite::forceCreate([
            'empresa_id' => $empresa->id, 'slug' => 's-' . uniqid(), 'titulo' => $nome, 'descricao' => 'Corte e barba no Arpoador.',
            'template_id' => SiteTemplate::where('slug', 'barbearia')->value('id'), 'dominio_personalizado' => $host,
            'whatsapp' => '5521999990000', 'ativo' => 1,
        ]);

        return compact('empresa', 'site');
    }

    private function barbeiro(Empresa $empresa, string $nome, bool $ativo = true): Professor
    {
        $u = Usuario::forceCreate(['nome' => $nome, 'email' => strtolower(str_replace(' ', '', $nome)) . '@t.invalid', 'password' => 'x', 'tipo_usuario' => 'Professor']);

        return Professor::forceCreate(['usuario_id' => $u->id, 'empresa_id' => $empresa->id, 'sobre' => '', 'avatar' => '', 'ativo' => $ativo]);
    }

    private function naFila(Empresa $empresa, Professor $b, int $quantos): void
    {
        for ($i = 0; $i < $quantos; $i++) {
            $u = Usuario::forceCreate(['nome' => "C$i", 'email' => "c{$i}{$b->id}@t.invalid", 'password' => 'x', 'tipo_usuario' => 'Aluno']);
            $aluno = \App\Models\Alunos::create(['usuario_id' => $u->id]);
            FilaAtendimento::create(['empresa_id' => $empresa->id, 'professor_id' => $b->id, 'aluno_id' => $aluno->id, 'status' => 'aguardando', 'chegou_em' => now()]);
        }
    }

    private function abrir(string $host = self::HOST)
    {
        return $this->get("http://$host/");
    }

    public function test_template_barbearia_esta_cadastrado(): void
    {
        $this->assertSame('site.barbearia', SiteTemplate::where('slug', 'barbearia')->value('path_view'));
    }

    public function test_site_mostra_nome_barbeiros_ativos_e_link_para_a_fila(): void
    {
        $x = $this->barbearia();
        $this->barbeiro($x['empresa'], 'Carlos Navalha');
        $this->barbeiro($x['empresa'], 'Barbeiro Inativo', false);
        $outra = $this->barbearia('barbearia', 'outra.com.br', 'Outra Casa');
        $this->barbeiro($outra['empresa'], 'Barbeiro Da Outra');

        $r = $this->abrir();

        $r->assertOk()->assertSee('Barbearia do Zé')->assertSee('Carlos Navalha')
            ->assertDontSee('Barbeiro Inativo')->assertDontSee('Barbeiro Da Outra')->assertDontSee('Outra Casa');
        $r->assertSee(route('barbearia.show', $x['empresa']), false)->assertSee('Entrar na fila');
    }

    public function test_site_mostra_a_fila_de_agora_so_desta_barbearia(): void
    {
        $x = $this->barbearia();
        $carlos = $this->barbeiro($x['empresa'], 'Carlos Navalha');
        $this->naFila($x['empresa'], $carlos, 2);
        $outra = $this->barbearia('barbearia', 'outra.com.br', 'Outra Casa');
        $this->naFila($outra['empresa'], $this->barbeiro($outra['empresa'], 'Beto'), 5);

        $this->abrir()->assertSee('2 pessoas na fila agora')->assertSee('2</b> na fila', false);

        FilaAtendimento::query()->update(['status' => 'concluido']);
        $this->abrir()->assertSee('Sem fila agora');
    }

    public function test_site_mostra_precos_horarios_endereco_e_instagram(): void
    {
        $x = $this->barbearia();
        $this->barbeiro($x['empresa'], 'Carlos');
        SiteServico::forceCreate(['site_id' => $x['site']->id, 'titulo' => 'Corte Social', 'descricao' => 'Tesoura e máquina', 'preco' => 45, 'duracao' => '40 min']);
        SiteContato::forceCreate(['site_id' => $x['site']->id, 'tipo' => 'horario', 'valor' => 'Seg a Sex: 9h às 20h']);
        SiteContato::forceCreate(['site_id' => $x['site']->id, 'tipo' => 'horario', 'valor' => 'Sábado: 9h às 17h']);
        SiteContato::forceCreate(['site_id' => $x['site']->id, 'tipo' => 'instagram', 'valor' => '@zebarbearia']);
        EmpresaEndereco::forceCreate(['empresa_id' => $x['empresa']->id, 'endereco' => 'Rua Teste, 10', 'cidade' => 'Rio de Janeiro', 'estado' => 'RJ', 'cep' => '22000000', 'uf' => 'RJ', 'pais' => 'Brasil']);

        $this->abrir()->assertSee('Corte Social')->assertSee('R$ 45,00')->assertSee('40 min')
            ->assertSee('Seg a Sex: 9h às 20h')->assertSee('Sábado: 9h às 17h')
            ->assertSee('Rua Teste, 10')->assertSee('Barbearia em Rio de Janeiro')
            ->assertSee('instagram.com/zebarbearia', false)->assertSee('&#64;zebarbearia', false)->assertDontSee('{{ $instagram }}', false);
    }

    public function test_site_sem_servicos_nem_horarios_nao_mostra_secoes_vazias(): void
    {
        $x = $this->barbearia();
        $this->barbeiro($x['empresa'], 'Carlos');

        $this->abrir()->assertOk()->assertDontSee('Serviços e preços')->assertDontSee('id="horarios"', false)->assertDontSee('id="avaliacoes"', false);
    }

    public function test_avaliacoes_e_link_do_google_aparecem_quando_existem(): void
    {
        $x = $this->barbearia();
        config(['sites.avaliacao_google' => [$x['site']->id => 'https://g.page/r/abc/review']]);
        SiteDepoimento::forceCreate(['site_id' => $x['site']->id, 'nome' => 'Marcos', 'nota' => 5, 'comentario' => 'Melhor degradê da cidade']);

        $this->abrir()->assertSee('Melhor degradê da cidade')->assertSee('Marcos')->assertSee('https://g.page/r/abc/review', false)->assertSee('href="#avaliacoes"', false);
    }

    public function test_site_inativo_nao_e_indexado_e_ativo_tem_canonical_e_dados_estruturados(): void
    {
        $x = $this->barbearia();
        $this->abrir()->assertDontSee('noindex', false)->assertSee('<link rel="canonical" href="https://' . self::HOST . '">', false)->assertSee('"@type":"HairSalon"', false);

        $x['site']->update(['ativo' => 0]);
        $this->abrir()->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_empresa_de_outro_nicho_com_o_template_nao_ganha_fila_e_chama_pelo_whatsapp(): void
    {
        $x = $this->barbearia('pilates', 'pilates-teste.com.br', 'Studio Pilates');
        $this->barbeiro($x['empresa'], 'Professora Ana');

        $r = $this->abrir('pilates-teste.com.br');

        $r->assertOk()->assertDontSee('Entrar na fila')->assertDontSee('Professora Ana')->assertDontSee('na fila agora')
            ->assertSee('Agendar pelo WhatsApp')->assertSee('https://wa.me/5521999990000', false);
    }

    public function test_pagina_do_checkin_abre_no_dominio_proprio_da_barbearia(): void
    {
        $x = $this->barbearia();
        $b = $this->barbeiro($x['empresa'], 'Carlos Navalha');

        $this->get('http://' . self::HOST . '/barbearia/' . $x['empresa']->id)->assertOk()->assertSee('Carlos Navalha');
        $this->get('http://' . self::HOST . '/barbearia/' . $x['empresa']->id . '/barbeiro/' . $b->id)->assertOk();
    }

    public function test_nome_com_html_e_escapado(): void
    {
        $x = $this->barbearia('barbearia', self::HOST, 'Zé <script>alert(1)</script>');
        $x['site']->update(['titulo' => 'Zé <script>alert(1)</script>']);

        $this->abrir()->assertDontSee('<script>alert(1)</script>', false);
    }
}
