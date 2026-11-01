<?php

namespace Tests\Feature;

use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\Modalidade;
use App\Models\PlanoAluno;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Planos de alunos pertencem a uma empresa: cada uma cria, ve e altera so os seus. */
class PlanosPorEmpresaTest extends TestCase
{
    use RefreshDatabase;

    private array $a;
    private array $b;

    protected function setUp(): void
    {
        parent::setUp();

        config(['master_users.emails' => ['master@teste.com']]);
        $m = Modalidade::forceCreate(['nome' => 'Pilates', 'nicho' => 'pilates']);
        $this->a = $this->criar('a', $m);
        $this->b = $this->criar('b', $m);
    }

    private function criar(string $k, Modalidade $m): array
    {
        $dono = Usuario::forceCreate(['nome' => "Dono $k", 'email' => "dono$k@teste.com", 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $empresa = Empresa::forceCreate(['user_id' => $dono->id, 'modalidade_id' => $m->id, 'nome' => "Empresa $k", 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '']);
        $professor = Professor::forceCreate(['usuario_id' => $dono->id, 'empresa_id' => $empresa->id, 'avatar' => 'a.png', 'sobre' => 's']);
        $alunoUser = Usuario::forceCreate(['nome' => "Aluno $k", 'email' => "aluno$k@teste.com", 'password' => 'x', 'tipo_usuario' => 'Aluno']);
        $aluno = Alunos::forceCreate(['usuario_id' => $alunoUser->id]);
        AlunoProfessor::forceCreate(['aluno_id' => $aluno->id, 'professor_id' => $professor->id]);
        $plano = PlanoAluno::create(['empresa_id' => $empresa->id, 'nome' => "Plano $k", 'valor' => 100, 'tipo' => 'livre']);

        return compact('dono', 'empresa', 'aluno', 'plano');
    }

    private function ajax(): array
    {
        return ['X-Requested-With' => 'XMLHttpRequest'];
    }

    public function test_plano_novo_nasce_na_empresa_do_usuario(): void
    {
        $this->actingAs($this->a['dono'])->post('/admin/planos', ['nome' => 'Mensal', 'valor' => 120])->assertRedirect();

        $this->assertSame($this->a['empresa']->id, PlanoAluno::where('nome', 'Mensal')->value('empresa_id'));
    }

    public function test_usuario_sem_empresa_nao_cria_plano(): void
    {
        $solto = Usuario::forceCreate(['nome' => 'Solto', 'email' => 'solto@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);

        $this->actingAs($solto)->post('/admin/planos', ['nome' => 'X', 'valor' => 1])->assertStatus(422);
        $this->assertSame(0, PlanoAluno::where('nome', 'X')->count());
    }

    public function test_nao_cria_plano_em_nome_de_outra_empresa(): void
    {
        $this->actingAs($this->a['dono'])
            ->post('/admin/planos', ['nome' => 'Intruso', 'valor' => 1, 'empresa_id' => $this->b['empresa']->id])
            ->assertForbidden();

        $this->assertSame(0, PlanoAluno::where('nome', 'Intruso')->count());
    }

    public function test_listagem_mostra_so_os_planos_da_propria_empresa(): void
    {
        $nomes = $this->actingAs($this->a['dono'])->get('/admin/planos')->viewData('planos')->pluck('nome')->all();

        $this->assertSame(['Plano a'], $nomes);
    }

    public function test_master_ve_os_planos_de_todas_as_empresas(): void
    {
        $master = Usuario::forceCreate(['nome' => 'M', 'email' => 'master@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);

        $this->assertCount(2, $this->actingAs($master)->get('/admin/planos')->viewData('planos'));
    }

    public function test_plano_de_outra_empresa_e_barrado_no_painel_e_na_api(): void
    {
        $id = $this->b['plano']->id;
        $a = $this->actingAs($this->a['dono']);

        $a->get("/admin/planos/$id/edit")->assertForbidden();
        $a->put("/admin/planos/$id", ['nome' => 'X', 'valor' => 1])->assertForbidden();
        $a->delete("/admin/planos/$id")->assertForbidden();
        $a->put("/api/planos-alunos/$id", ['nome' => 'X'], $this->ajax())->assertForbidden();
        $a->delete("/api/planos-alunos/$id", [], $this->ajax())->assertForbidden();

        $this->assertNotNull(PlanoAluno::find($id));
    }

    public function test_altera_e_apaga_o_plano_da_propria_empresa(): void
    {
        $id = $this->a['plano']->id;

        $this->actingAs($this->a['dono'])->put("/admin/planos/$id", ['nome' => 'Novo nome', 'valor' => 50])->assertRedirect();
        $this->assertSame('Novo nome', PlanoAluno::find($id)->nome);

        $this->actingAs($this->a['dono'])->delete("/admin/planos/$id")->assertRedirect();
        $this->assertNull(PlanoAluno::find($id));
    }

    public function test_tela_de_vincular_lista_so_alunos_e_planos_da_propria_empresa(): void
    {
        $dados = $this->actingAs($this->a['dono'])->get('/admin/planos/vincular')->viewData('alunos')->pluck('id')->all();
        $planos = $this->actingAs($this->a['dono'])->get('/admin/planos/vincular')->viewData('planos')->pluck('nome')->all();

        $this->assertSame([$this->a['aluno']->id], $dados);
        $this->assertSame(['Plano a'], $planos);
    }

    public function test_nao_vincula_aluno_ou_plano_de_outra_empresa(): void
    {
        $a = $this->actingAs($this->a['dono']);
        $ok = ['status' => 'ativo'];

        $a->post('/admin/planos/vincular', $ok + ['aluno_id' => $this->b['aluno']->id, 'plano_id' => $this->a['plano']->id])->assertForbidden();
        $a->post('/admin/planos/vincular', $ok + ['aluno_id' => $this->a['aluno']->id, 'plano_id' => $this->b['plano']->id])->assertForbidden();

        $this->assertSame(0, \DB::table('aluno_planos')->count());
    }

    public function test_vincula_aluno_e_plano_da_propria_empresa(): void
    {
        $this->actingAs($this->a['dono'])
            ->post('/admin/planos/vincular', ['status' => 'ativo', 'aluno_id' => $this->a['aluno']->id, 'plano_id' => $this->a['plano']->id])
            ->assertRedirect();

        $this->assertSame(1, \DB::table('aluno_planos')->count());
    }

    public function test_api_publica_lista_planos_de_uma_empresa_so_com_o_filtro(): void
    {
        $this->getJson('/api/planos-alunos')->assertOk()->assertExactJson([]);

        $nomes = collect($this->getJson('/api/planos-alunos?empresa_id=' . $this->b['empresa']->id)->assertOk()->json())->pluck('nome')->all();
        $this->assertSame(['Plano b'], $nomes);
    }
}
