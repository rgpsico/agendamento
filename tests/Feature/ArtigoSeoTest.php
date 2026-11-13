<?php

namespace Tests\Feature;

use App\Console\Commands\GerarArtigoSeo;
use App\Models\Empresa;
use App\Models\EmpresaSite;
use App\Models\Modalidade;
use App\Models\SiteArtigo;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use ReflectionMethod;
use Tests\TestCase;

class ArtigoSeoTest extends TestCase
{
    use RefreshDatabase;

    private string $temaProposto = '';
    private string $tituloGerado = '';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.deepseek.key' => 'fake', 'services.unsplash.access_key' => null]);

        // IA simulada: max_tokens 80 e o pedido de tema; o outro e o artigo completo
        Http::fake(function ($request) {
            if ($request->url() !== 'https://api.deepseek.com/v1/chat/completions') {
                return Http::response([], 404);
            }
            $conteudo = ($request->data()['max_tokens'] ?? 0) === 80
                ? $this->temaProposto
                : "TÍTULO: {$this->tituloGerado}\nRESUMO: resumo\nCONTEUDO: <p>texto</p>";

            return Http::response(['choices' => [['message' => ['content' => $conteudo]]]]);
        });
    }

    private function criarSite(string $nicho, string $cidade = 'Rio de Janeiro'): EmpresaSite
    {
        $modalidade = Modalidade::forceCreate(['nome' => ucfirst($nicho), 'nicho' => $nicho]);
        $usuario = Usuario::forceCreate(['nome' => 'Dono', 'email' => uniqid() . '@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $empresa = Empresa::forceCreate([
            'user_id' => $usuario->id, 'modalidade_id' => $modalidade->id,
            'nome' => 'Estudio Teste', 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '',
        ]);
        $site = EmpresaSite::forceCreate(['empresa_id' => $empresa->id, 'slug' => 's' . uniqid(), 'titulo' => 'Estudio Teste', 'ativo' => 1, 'blog_automatico' => 1]);
        \App\Models\EmpresaEndereco::forceCreate(['empresa_id' => $empresa->id, 'endereco' => 'Rua A', 'cidade' => $cidade, 'estado' => 'RJ', 'uf' => 'RJ', 'cep' => '', 'pais' => 'Brasil']);

        return $site;
    }

    private function gerar(EmpresaSite $site): void
    {
        $this->artisan('artigo:gerar', ['--site_id' => $site->id, '--publicar' => true])->assertExitCode(0);
    }

    private function artigoExistente(EmpresaSite $site, string $titulo): void
    {
        SiteArtigo::forceCreate([
            'site_id' => $site->id, 'titulo' => $titulo, 'slug' => \Illuminate\Support\Str::slug($titulo),
            'resumo' => 'r', 'conteudo' => '<p>c</p>', 'status' => 'publicado',
        ]);
    }

    /** @test */
    public function expoe_nome_nicho_e_cidade_do_cadastro(): void
    {
        $site = $this->criarSite('pilates', 'Niteroi');

        $this->assertSame('Estudio Teste', $site->nome_empresa);
        $this->assertSame('pilates', $site->nicho);
        $this->assertSame('Niteroi', $site->cidade);
    }

    /** @test */
    public function publica_artigo_com_o_tema_proposto_pela_ia(): void
    {
        $this->temaProposto = 'Pilates para corredores: como melhorar a mobilidade do quadril';
        $this->tituloGerado = 'Pilates para corredores: como melhorar a mobilidade do quadril';
        $site = $this->criarSite('pilates');

        $this->gerar($site);

        $this->assertDatabaseHas('site_artigos', ['site_id' => $site->id, 'titulo' => $this->tituloGerado, 'status' => 'publicado']);
    }

    /** @test */
    public function nao_publica_artigo_do_mesmo_assunto_de_outro_site_do_mesmo_nicho(): void
    {
        $titulo = 'Pilates para corredores: como melhorar a mobilidade do quadril';
        $this->artigoExistente($this->criarSite('pilates'), $titulo);

        // a IA insiste no tema repetido e o artigo gerado tambem sai igual
        $this->temaProposto = $titulo;
        $this->tituloGerado = 'Como melhorar a mobilidade do quadril com pilates para corredores';
        $irmao = $this->criarSite('pilates');

        $this->gerar($irmao);

        $this->assertSame(0, SiteArtigo::where('site_id', $irmao->id)->count());
    }

    /** @test */
    public function nao_gera_artigo_para_nicho_sem_lista_de_temas(): void
    {
        $site = $this->criarSite('estetica');

        $this->gerar($site);

        $this->assertSame(0, SiteArtigo::count());
        Http::assertNothingSent();
    }

    /** @test */
    public function nao_cita_preco_inventado_no_prompt(): void
    {
        $this->temaProposto = 'Pilates para gestantes: exercicios seguros no segundo trimestre';
        $this->tituloGerado = $this->temaProposto;
        $site = $this->criarSite('pilates');

        $this->gerar($site);

        Http::assertSent(function ($request) {
            $texto = json_encode($request->data(), JSON_UNESCAPED_UNICODE);
            return str_contains($texto, 'NÃO cite preços') && !str_contains($texto, '280');
        });
    }

    /** @test */
    public function compara_titulos_ignorando_acento_e_plural(): void
    {
        $m = new ReflectionMethod(new GerarArtigoSeo(), 'tituloRepetido');
        $m->setAccessible(true);
        $c = new GerarArtigoSeo();
        $existentes = [
            'Ipanema e Arpoador: o guia definitivo de atividades esportivas',
            'Pilates na gravidez: benefícios e cuidados',
            'Aula de surf no Arpoador para iniciantes: como é e quanto custa',
        ];

        $repetidos = [
            'Ipanema e Arpoador: o guia definitivo de atividades esportivas',
            'Atividades esportivas em Ipanema e no Arpoador: o guia definitivo',
            'Pilates na gravidez: benefícios e cuidados essenciais para uma gestação saudável',
            'Aula de surf no Arpoador: quanto custa para iniciantes',
        ];
        $diferentes = [
            'Pilates para idosos: benefícios e cuidados na terceira idade',
            'Como escolher a prancha certa para o seu nível e peso',
            'Pilates para iniciantes: por onde começar',
        ];

        foreach ($repetidos as $t) {
            $this->assertTrue($m->invoke($c, $t, $existentes), "deveria repetir: $t");
        }
        foreach ($diferentes as $t) {
            $this->assertFalse($m->invoke($c, $t, $existentes), "nao deveria repetir: $t");
        }
    }

    /** @test */
    public function site_sem_blog_automatico_marcado_nao_recebe_artigo(): void
    {
        Http::fake();
        $site = $this->criarSite('pilates');
        $site->update(['blog_automatico' => false]);

        $this->gerar($site);

        $this->assertDatabaseMissing('site_artigos', ['site_id' => $site->id]);
        Http::assertNothingSent();
    }
}
