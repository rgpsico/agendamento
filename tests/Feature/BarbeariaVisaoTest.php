<?php

namespace Tests\Feature;

use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\FilaAtendimento;
use App\Models\Modalidade;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/** Painel do dono: visão geral ao vivo (filas de todos os barbeiros, números do dia, últimos 7 dias). */
class BarbeariaVisaoTest extends TestCase
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

    private function fila(string $nome, array $b, string $status, array $extra = []): FilaAtendimento
    {
        $u = Usuario::forceCreate(['nome' => $nome, 'email' => strtolower($nome) . random_int(1, 99999) . '@celular.invalid', 'password' => 'x', 'tipo_usuario' => 'Aluno', 'telefone' => (string) random_int(21900000000, 21999999999)]);
        $aluno = Alunos::create(['usuario_id' => $u->id]);

        return FilaAtendimento::create($extra + [
            'empresa_id' => $b['empresa']->id, 'professor_id' => $b['barbeiro']->id, 'aluno_id' => $aluno->id,
            'status' => $status, 'chegou_em' => now()->subMinutes(30),
        ]);
    }

    public function test_so_o_dono_abre_a_visao_e_visitante_vai_para_a_entrada(): void
    {
        $this->get(route('barbearia.visao'))->assertRedirect(route('barbearia.login'));
        $this->actingAs($this->a['user'])->get(route('barbearia.visao'))->assertForbidden();
        $this->actingAs($this->a['dono'])->get(route('barbearia.visao'))->assertOk()->assertSee('Visão geral')->assertSee('Painel do dono');
    }

    public function test_painel_do_dono_leva_para_a_visao_geral(): void
    {
        $this->actingAs($this->a['dono'])->get(route('barbearia.painel'))->assertRedirect(route('barbearia.visao'));
    }

    public function test_mostra_so_a_fila_da_propria_barbearia_com_o_nome_do_barbeiro(): void
    {
        $this->fila('Joao', $this->a, 'aguardando');
        $this->fila('Intruso', $this->b, 'aguardando');

        $this->actingAs($this->a['dono'])->get(route('barbearia.visao'))->assertOk()
            ->assertSee('Joao')->assertSee('com Barbeiro A')->assertDontSee('Intruso')->assertDontSee('Barbeiro B');
    }

    public function test_numeros_do_dia_espera_e_duracao_medias(): void
    {
        // espera 20 min (chegou 10:00, iniciou 10:20) e corte de 30 min (10:20 -> 10:50)
        $this->fila('Pedro', $this->a, 'concluido', ['chegou_em' => now()->subHours(3), 'iniciado_em' => now()->subHours(3)->addMinutes(20), 'finalizado_em' => now()->subHours(3)->addMinutes(50)]);
        $this->fila('Ausente', $this->a, 'ausente', ['finalizado_em' => now()->subHour()]);
        $this->fila('Desistente', $this->a, 'desistiu', ['finalizado_em' => now()->subHour()]);
        $this->fila('Atendendo', $this->a, 'atendendo', ['iniciado_em' => now()->subMinutes(5)]);

        $r = $this->actingAs($this->a['dono'])->get(route('barbearia.visao'))->assertOk();
        $r->assertSeeInOrder(['0', 'na fila agora', '1', 'em atendimento', '1', 'atendidos hoje', '20 min', 'espera média', '30 min', 'duração média']);
        $r->assertSee('1 não apareceram')->assertSee('1 desistiram')->assertSee('atendendo Atendendo');
    }

    public function test_sem_atendimentos_mostra_traco_e_nao_quebra(): void
    {
        $this->actingAs($this->a['dono'])->get(route('barbearia.visao'))->assertOk()->assertSee('—')->assertSee('Ninguém esperando agora.')->assertSee('<strong>0</strong>', false)->assertSee('atendimentos concluídos na semana');
    }

    public function test_ultimos_7_dias_conta_so_concluidos_da_semana(): void
    {
        $this->fila('Hoje', $this->a, 'concluido', ['iniciado_em' => now()->subHour(), 'finalizado_em' => now()->subMinutes(30)]);
        $this->fila('Ontem', $this->a, 'concluido', ['iniciado_em' => now()->subDay(), 'finalizado_em' => now()->subDay()]);
        $this->fila('Velho', $this->a, 'concluido', ['iniciado_em' => now()->subDays(10), 'finalizado_em' => now()->subDays(10)]);
        $this->fila('Outra', $this->b, 'concluido', ['iniciado_em' => now()->subHour(), 'finalizado_em' => now()->subMinutes(30)]);

        $this->actingAs($this->a['dono'])->get(route('barbearia.visao'))->assertOk()->assertSee('<strong>2</strong>', false)->assertSee('atendimentos concluídos na semana');
    }

    public function test_fragmento_vem_sem_o_layout(): void
    {
        $this->actingAs($this->a['dono'])->get(route('barbearia.visao', ['fragmento' => 1]))->assertOk()->assertSee('na fila agora')->assertDontSee('<html', false)->assertDontSee('Visão geral');
    }

    public function test_dono_com_duas_barbearias_escolhe_e_nao_ve_a_de_outro(): void
    {
        $segunda = Empresa::forceCreate(['user_id' => $this->a['dono']->id, 'modalidade_id' => $this->a['empresa']->modalidade_id, 'nome' => 'Barbearia A2', 'descricao' => 'd', 'telefone' => '1', 'cnpj' => '', 'status' => 'ativo']);

        $this->actingAs($this->a['dono'])->get(route('barbearia.visao', ['empresa' => $segunda->id]))->assertOk()->assertSee('Barbearia A2')->assertSee('<select', false);
        // pedir a barbearia de outro dono cai na dele
        $this->actingAs($this->a['dono'])->get(route('barbearia.visao', ['empresa' => $this->b['empresa']->id]))->assertOk()->assertDontSee('Barbearia B');
    }
}
