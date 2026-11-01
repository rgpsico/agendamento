<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\AlunoProfessor;
use App\Models\Alunos;
use App\Models\Aulas;
use App\Models\Disponibilidade;
use App\Models\Empresa;
use App\Models\EmpresaGaleria;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Um usuario logado so altera dados da PROPRIA empresa. Cada teste cria duas empresas (A e B),
 * loga como o dono de A e tenta mexer nos dados de B (deve receber 403) e nos de A (deve passar).
 */
class IsolamentoEmpresaTest extends TestCase
{
    use RefreshDatabase;

    private array $a;
    private array $b;

    protected function setUp(): void
    {
        parent::setUp();

        config(['master_users.emails' => ['master@teste.com']]);
        DB::table('dias_da_semana')->insertOrIgnore(['id' => 1, 'dia' => 'segunda', 'nome_dia' => 'Segunda']);
        $this->modalidade = Modalidade::forceCreate(['nome' => 'Pilates', 'nicho' => 'pilates']);
        $this->a = $this->criarEmpresa('a');
        $this->b = $this->criarEmpresa('b');
    }

    private Modalidade $modalidade;

    private function criarEmpresa(string $k): array
    {
        $dono = Usuario::forceCreate(['nome' => "Dono $k", 'email' => "dono$k@teste.com", 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $empresa = Empresa::forceCreate(['user_id' => $dono->id, 'modalidade_id' => $this->modalidade->id, 'nome' => "Empresa $k", 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '']);
        $professor = Professor::forceCreate(['usuario_id' => $dono->id, 'empresa_id' => $empresa->id, 'avatar' => 'a.png', 'sobre' => 's']);
        $servico = Servicos::forceCreate(['empresa_id' => $empresa->id, 'titulo' => 'S', 'imagem' => '', 'preco' => 0, 'tempo_de_aula' => 60]);
        $aula = Aulas::forceCreate(['professor_id' => $professor->id, 'capacidade' => 1, 'data_hora' => now(), 'dia_id' => 1, 'local' => 'x']);
        $disp = Disponibilidade::forceCreate(['id_professor' => $professor->id, 'id_servico' => $servico->id, 'id_dia' => 1, 'hora_inicio' => '08:00:00', 'hora_fim' => '09:00:00']);
        $alunoUser = Usuario::forceCreate(['nome' => "Aluno $k", 'email' => "aluno$k@teste.com", 'password' => 'x', 'tipo_usuario' => 'Aluno']);
        $aluno = Alunos::forceCreate(['usuario_id' => $alunoUser->id]);
        AlunoProfessor::forceCreate(['aluno_id' => $aluno->id, 'professor_id' => $professor->id]);
        $agenda = Agendamento::forceCreate(['aluno_id' => $aluno->id, 'professor_id' => $professor->id, 'data_da_aula' => now()->toDateString(), 'valor_aula' => 10]);
        $foto = EmpresaGaleria::forceCreate(['empresa_id' => $empresa->id, 'image' => 'x.jpg']);

        return compact('dono', 'empresa', 'professor', 'servico', 'aula', 'disp', 'alunoUser', 'aluno', 'agenda', 'foto');
    }

    private function como(Usuario $u, string $metodo, string $url, array $dados = []): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($u)->json($metodo, $url, $dados, ['X-Requested-With' => 'XMLHttpRequest']);
    }

    public static function alheios(): array
    {
        // [metodo, url com {b.<chave>} do registro da empresa B]
        $l = [
            ['DELETE', '/api/servicos/{servico}'], ['PUT', '/api/servicos/{servico}'],
            ['DELETE', '/api/empresas/{empresa}'], ['PUT', '/api/empresas/{empresa}'],
            ['DELETE', '/api/professor/{professor}'], ['PUT', '/api/professor/{professor}'],
            ['DELETE', '/api/aulas/{aula}'], ['PUT', '/api/aulas/{aula}'],
            ['DELETE', '/api/disponibilidade/{disp}'], ['PUT', '/api/disponibilidade/{disp}'],
            ['PUT', '/api/agendamento/{agenda}'], ['DELETE', '/api/frontend/agendamentos/{agenda}'],
            ['DELETE', '/api/gallery/{foto}'],
            ['DELETE', '/api/users/{alunoUser}'], ['PUT', '/api/users/{alunoUser}'], ['GET', '/api/users/{alunoUser}'],
            ['DELETE', '/api/users/{dono}'],
            ['POST', '/api/aluno/{aluno}/update'], ['POST', '/api/aluno/{aluno}/update-endereco'],
            ['DELETE', '/api/aluno/{aluno}/destroy/{professor}'],
        ];

        return array_combine(array_map(fn ($x) => "$x[0] $x[1]", $l), $l);
    }

    private function montar(string $url, array $reg): string
    {
        return preg_replace_callback('/\{(\w+)\}/', fn ($m) => $reg[$m[1]]->getKey(), $url);
    }

    /** @dataProvider alheios */
    public function test_nao_mexe_em_dados_de_outra_empresa(string $metodo, string $url): void
    {
        $this->como($this->a['dono'], $metodo, $this->montar($url, $this->b))->assertForbidden();
    }

    /** @dataProvider alheios */
    public function test_mexe_nos_dados_da_propria_empresa(string $metodo, string $url): void
    {
        $status = $this->como($this->a['dono'], $metodo, $this->montar($url, $this->a))->getStatusCode();

        $this->assertNotContains($status, [401, 403], "$metodo $url na propria empresa nao deveria ser barrado");
    }

    public function test_nao_cria_registro_em_nome_de_outra_empresa(): void
    {
        $b = $this->b;
        $dono = $this->a['dono'];

        $this->como($dono, 'POST', '/api/servicos', ['empresa_id' => $b['empresa']->id, 'titulo' => 'X', 'preco' => 1, 'tempo_de_aula' => 30])->assertForbidden();
        $this->como($dono, 'POST', '/api/professor', ['empresa_id' => $b['empresa']->id])->assertForbidden();
        $this->como($dono, 'POST', '/api/aulas', ['professor_id' => $b['professor']->id])->assertForbidden();
        $this->como($dono, 'POST', '/api/disponibilidade', ['id_professor' => $b['professor']->id])->assertForbidden();
        $this->como($dono, 'POST', '/api/asaas/criarChavePix', ['user_id' => $b['dono']->id, 'empresa_id' => $b['empresa']->id])->assertForbidden();
        $this->como($dono, 'POST', '/api/empresas', ['user_id' => $b['dono']->id])->assertForbidden();

        $this->assertSame(1, Servicos::where('empresa_id', $b['empresa']->id)->count());
    }

    public function test_cria_registro_na_propria_empresa(): void
    {
        $a = $this->a;

        $status = $this->como($a['dono'], 'POST', '/api/servicos', ['empresa_id' => $a['empresa']->id, 'titulo' => 'Novo', 'preco' => 1, 'tempo_de_aula' => 30])->getStatusCode();

        $this->assertNotContains($status, [401, 403]);
    }

    public function test_listar_todos_os_usuarios_e_so_do_master(): void
    {
        $this->como($this->a['dono'], 'GET', '/api/users')->assertForbidden();

        $master = Usuario::forceCreate(['nome' => 'M', 'email' => 'master@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $this->como($master, 'GET', '/api/users')->assertOk();
    }

    public static function soMaster(): array
    {
        $l = [
            ['POST', '/api/dias'], ['DELETE', '/api/dias/1'], ['POST', '/api/modalidade/1/update'], ['DELETE', '/api/modalidade/1/destroy'],
            ['POST', '/api/permissions/store'], ['PUT', '/api/permissions/update/1'], ['POST', '/api/permissions/delete/1'],
            ['POST', '/api/google-ads/create-campaign'], ['POST', '/api/admin/asaas/limpar-sandbox'], ['POST', '/api/pix-simulate'],
            ['POST', '/api/pix/key/delete'], ['DELETE', '/api/pix/keys/1'], ['POST', '/api/send-whatsapp'], ['POST', '/api/whatsapp/send'],
            ['POST', '/api/email/teste'], ['POST', '/api/push/test'], ['POST', '/api/openai/test'], ['POST', '/api/events'],
        ];

        return array_combine(array_map(fn ($x) => "$x[0] $x[1]", $l), $l);
    }

    /** @dataProvider soMaster */
    public function test_recursos_globais_so_para_o_master(string $metodo, string $url): void
    {
        $this->como($this->a['dono'], $metodo, $url)->assertForbidden();

        $master = Usuario::forceCreate(['nome' => 'M', 'email' => 'master@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $status = $this->como($master, $metodo, $url)->getStatusCode();
        $this->assertNotContains($status, [401, 403], "master deveria passar em $metodo $url");
    }

    public function test_master_acessa_dados_de_qualquer_empresa(): void
    {
        $master = Usuario::forceCreate(['nome' => 'M', 'email' => 'master@teste.com', 'password' => 'x', 'tipo_usuario' => 'Professor']);

        $status = $this->como($master, 'PUT', '/api/servicos/' . $this->b['servico']->id, ['titulo' => 'Y'])->getStatusCode();

        $this->assertNotContains($status, [401, 403]);
    }
}
