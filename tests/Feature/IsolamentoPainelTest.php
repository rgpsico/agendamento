<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** Telas e acoes do painel web: o usuario da empresa A nao abre nem altera registros da empresa B. */
class IsolamentoPainelTest extends TestCase
{
    use RefreshDatabase;

    private array $a;
    private array $b;
    private Modalidade $modalidade;

    protected function setUp(): void
    {
        parent::setUp();

        config(['master_users.emails' => ['master@teste.com']]);
        DB::table('dias_da_semana')->insertOrIgnore(['id' => 1, 'dia' => 'segunda', 'nome_dia' => 'Segunda']);
        DB::table('despesas_categorias')->insertOrIgnore(['id' => 1, 'nome' => 'Geral']);
        $this->modalidade = Modalidade::forceCreate(['nome' => 'Pilates', 'nicho' => 'pilates']);
        $this->a = $this->criar('a');
        $this->b = $this->criar('b');
    }

    private function criar(string $k): array
    {
        $dono = Usuario::forceCreate(['nome' => "Dono $k", 'email' => "dono$k@teste.com", 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $empresa = Empresa::forceCreate(['user_id' => $dono->id, 'modalidade_id' => $this->modalidade->id, 'nome' => "Empresa $k", 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '']);
        $professor = Professor::forceCreate(['usuario_id' => $dono->id, 'empresa_id' => $empresa->id, 'avatar' => 'a.png', 'sobre' => 's']);
        $servico = Servicos::forceCreate(['empresa_id' => $empresa->id, 'titulo' => 'S', 'imagem' => '', 'preco' => 0, 'tempo_de_aula' => 60]);
        $alunoUser = Usuario::forceCreate(['nome' => "Aluno $k", 'email' => "aluno$k@teste.com", 'password' => 'x', 'tipo_usuario' => 'Aluno']);
        $aluno = Alunos::forceCreate(['usuario_id' => $alunoUser->id]);
        AlunoProfessor::forceCreate(['aluno_id' => $aluno->id, 'professor_id' => $professor->id]);
        $agenda = Agendamento::forceCreate(['aluno_id' => $aluno->id, 'professor_id' => $professor->id, 'data_da_aula' => now()->toDateString(), 'valor_aula' => 10]);

        $siteId = DB::table('empresa_site')->insertGetId(['empresa_id' => $empresa->id, 'slug' => "site-$k"]);
        $artigoId = DB::table('site_artigos')->insertGetId(['site_id' => $siteId, 'titulo' => 'T', 'slug' => "t-$k", 'conteudo' => '<p>c</p>']);
        $contatoId = DB::table('site_contatos')->insertGetId(['site_id' => $siteId, 'tipo' => 'email', 'valor' => 'x@y.z']);
        $botId = DB::table('bots')->insertGetId(['nome' => 'Bot', 'empresa_id' => $empresa->id]);
        $despesaId = DB::table('despesas')->insertGetId(['categoria_id' => 1, 'descricao' => 'd', 'empresa_id' => $empresa->id, 'usuario_id' => $dono->id, 'valor' => 1]);
        $receitaId = DB::table('receitas')->insertGetId(['empresa_id' => $empresa->id, 'usuario_id' => $dono->id, 'valor' => 1]);

        return compact('dono', 'empresa', 'professor', 'servico', 'alunoUser', 'aluno', 'agenda', 'siteId', 'artigoId', 'contatoId', 'botId', 'despesaId', 'receitaId');
    }

    private function url(string $modelo, array $r): string
    {
        return preg_replace_callback('/\{(\w+)\}/', function ($m) use ($r) {
            $v = $r[$m[1]];

            return is_object($v) ? $v->getKey() : $v;
        }, $modelo);
    }

    public static function telas(): array
    {
        $l = [
            ['GET', '/cliente/servicos/{servico}/edit'], ['POST', '/cliente/servicos/{servico}/update'], ['DELETE', '/cliente/servicos/{servico}/delete'],
            ['GET', '/cliente/empresa/agenda/{agenda}/edit'], ['POST', '/cliente/empresa/agenda/{agenda}/update'], ['DELETE', '/cliente/empresa/agenda/{agenda}/destroy'],
            ['GET', '/cliente/empresa/alunos/{aluno}/edit'], ['POST', '/cliente/empresa/alunos/{aluno}/update'], ['DELETE', '/cliente/empresa/alunos/{aluno}/destroy'],
            ['GET', '/cliente/empresa/{empresa}/show'], ['GET', '/cliente/empresa/{empresa}/edit'], ['DELETE', '/cliente/empresa/{empresa}/destroy'], ['POST', '/cliente/empresa/{empresa}/restore'],
            ['GET', '/cliente/empresa/{dono}/config'], ['POST', '/cliente/empresa/{dono}/profile'], ['POST', '/cliente/empresa/{dono}/endereco_empresa'],
            ['GET', '/admin/usuarios/{alunoUser}/edit'], ['PUT', '/admin/usuarios/{alunoUser}'], ['DELETE', '/admin/usuarios/{alunoUser}'],
            ['GET', '/admin/usuarios/{dono}/permissions'], ['POST', '/admin/usuarios/{dono}/permissions'],
            ['GET', '/admin/site/edit/{siteId}'], ['DELETE', '/admin/site/{siteId}'], ['POST', '/admin/site/{siteId}/dominio'], ['PUT', '/admin/site/{siteId}/configuracoes'],
            ['GET', '/admin/site/artigos/{artigoId}/edit'], ['DELETE', '/admin/site/artigos/{artigoId}'],
            ['GET', '/admin/site/contatos/{contatoId}/edit'], ['DELETE', '/admin/site/contatos/{contatoId}/destroy'],
            ['GET', '/admin/bot/edit/{botId}'], ['PUT', '/admin/bot/update/{botId}'], ['DELETE', '/admin/bot/destroy/{botId}'],
            ['GET', '/financeiro/despesas/{despesaId}/edit'], ['PUT', '/financeiro/despesas/{despesaId}'], ['DELETE', '/financeiro/despesas/{despesaId}'],
            ['GET', '/financeiro/receitas/{receitaId}/edit'], ['PUT', '/financeiro/receitas/{receitaId}'], ['DELETE', '/financeiro/receitas/{receitaId}'],
        ];

        return array_combine(array_map(fn ($x) => "$x[0] $x[1]", $l), $l);
    }

    /** @dataProvider telas */
    public function test_nao_acessa_registro_de_outra_empresa(string $metodo, string $modelo): void
    {
        $this->actingAs($this->a['dono'])->call($metodo, $this->url($modelo, $this->b))->assertForbidden();
    }

    /** @dataProvider telas */
    public function test_acessa_o_registro_da_propria_empresa(string $metodo, string $modelo): void
    {
        // permissions e so do master; as demais nao podem ser barradas pela regra de empresa (o controller pode responder 302/422/500)
        $status = $this->actingAs($this->a['dono'])->call($metodo, $this->url($modelo, $this->a))->getStatusCode();

        if (str_contains($modelo, '/permissions')) {
            $this->assertSame(403, $status);
        } else {
            $this->assertNotSame(403, $status, "$metodo $modelo na propria empresa foi barrado");
        }
    }

    public function test_modalidade_global_so_para_o_master(): void
    {
        $this->actingAs($this->a['dono'])->post('/cliente/empresa/modalidade/' . $this->modalidade->id . '/update')->assertForbidden();
        $this->actingAs($this->a['dono'])->delete('/cliente/empresa/modalidade/' . $this->modalidade->id . '/destroy')->assertForbidden();
    }

    public function test_master_acessa_qualquer_empresa(): void
    {
        $master = Usuario::forceCreate(['nome' => 'M', 'email' => 'master@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);

        $status = $this->actingAs($master)->get('/cliente/servicos/' . $this->b['servico']->id . '/edit')->getStatusCode();

        $this->assertNotSame(403, $status);
    }
}
