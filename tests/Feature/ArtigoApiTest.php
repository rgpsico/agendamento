<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\EmpresaSite;
use App\Models\Modalidade;
use App\Models\SiteArtigo;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArtigoApiTest extends TestCase
{
    use RefreshDatabase;

    private function criarSite(): EmpresaSite
    {
        $m = Modalidade::forceCreate(['nome' => 'Pilates', 'nicho' => 'pilates']);
        $u = Usuario::forceCreate(['nome' => 'Dono', 'email' => uniqid() . '@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $e = Empresa::forceCreate(['user_id' => $u->id, 'modalidade_id' => $m->id, 'nome' => 'Est', 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '']);

        return EmpresaSite::forceCreate(['empresa_id' => $e->id, 'slug' => 's' . uniqid(), 'titulo' => 'Est', 'ativo' => 1]);
    }

    private function payload(int $siteId): array
    {
        return ['site_id' => $siteId, 'titulo' => 'Titulo', 'conteudo' => '<p>x</p>', 'status' => 'publicado'];
    }

    /** @test */
    public function visitante_nao_cria_artigo(): void
    {
        $site = $this->criarSite();

        $this->postJson('/api/site-artigos', $this->payload($site->id))->assertUnauthorized();
        $this->postJson('/api/teste/artigos', $this->payload($site->id))->assertUnauthorized();
        $this->assertSame(0, SiteArtigo::count());
    }

    /** @test */
    public function usuario_com_token_cria_artigo(): void
    {
        $site = $this->criarSite();
        Sanctum::actingAs(Usuario::first());

        $this->postJson('/api/site-artigos', $this->payload($site->id))->assertCreated();
        $this->assertSame(1, SiteArtigo::count());
    }

    /** @test */
    public function listagem_publica_nao_mostra_rascunho(): void
    {
        $site = $this->criarSite();
        foreach (['publicado' => 'Visivel', 'rascunho' => 'Escondido'] as $status => $titulo) {
            SiteArtigo::forceCreate(['site_id' => $site->id, 'titulo' => $titulo, 'slug' => strtolower($titulo), 'resumo' => 'r', 'conteudo' => '<p>c</p>', 'status' => $status]);
        }

        $titulos = collect($this->getJson('/api/site-artigos?site_id=' . $site->id)->assertOk()->json())->pluck('titulo')->all();

        $this->assertSame(['Visivel'], $titulos);
    }
}
