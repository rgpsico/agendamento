<?php

namespace Tests\Feature;

use App\Http\Controllers\BotController;
use App\Http\Controllers\BotServiceController;
use App\Models\Bot;
use App\Models\BotService;
use App\Models\Empresa;
use App\Models\Servicos;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

/**
 * Cada empresa só enxerga e altera os PRÓPRIOS bots.
 *
 * Roda em SQLite na memória com só as tabelas necessárias — nunca toca o banco de produção.
 * Uso: DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan test --filter=BotIsolamentoTest
 */
class BotIsolamentoTest extends TestCase
{
    private Usuario $donoA;
    private Empresa $a;
    private Empresa $b;
    private Bot $botA;
    private Bot $botB;
    private Servicos $servA;
    private Servicos $servB;

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'sqlite', 'database.connections.sqlite' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => false]]);
        DB::purge('sqlite');
        $this->assertSame(':memory:', DB::connection()->getDatabaseName(), 'o teste não pode usar o banco real');

        Schema::create('usuarios', function ($t) {
            $t->id(); $t->string('nome'); $t->string('email')->unique(); $t->string('password'); $t->string('tipo_usuario')->default('Professor');
            $t->integer('isAdmin')->nullable(); $t->rememberToken(); $t->timestamp('data_nascimento')->nullable(); $t->string('telefone')->nullable(); $t->timestamps();
        });
        Schema::create('empresa', function ($t) {
            $t->id(); $t->unsignedBigInteger('user_id'); $t->unsignedBigInteger('modalidade_id')->default(1); $t->date('data_vencimento')->nullable();
            $t->string('avatar')->default(''); $t->string('nome'); $t->string('descricao')->default(''); $t->string('telefone')->default(''); $t->string('cnpj')->default('');
            $t->decimal('valor_aula_de', 8, 2)->nullable(); $t->decimal('valor_aula_ate', 8, 2)->nullable(); $t->string('banners')->nullable(); $t->string('status')->default('ativo'); $t->timestamps();
        });
        Schema::create('bots', function ($t) {
            $t->id(); $t->unsignedBigInteger('empresa_id'); $t->string('nome'); $t->text('prompt')->nullable(); $t->string('widget_token')->nullable();
            $t->boolean('widget_ativo')->default(false); $t->string('widget_cor')->default('#2a5298'); $t->string('widget_posicao')->default('direito');
            $t->string('widget_saudacao')->nullable(); $t->string('widget_avatar_url')->nullable(); $t->string('widget_nome_bot')->nullable();
            $t->string('segmento')->nullable(); $t->string('tom')->nullable(); $t->string('status')->default('1'); $t->string('token_deepseek')->nullable(); $t->timestamps();
        });
        Schema::create('servicos', function ($t) {
            $t->id(); $t->unsignedBigInteger('empresa_id'); $t->string('titulo'); $t->text('descricao')->nullable(); $t->decimal('preco', 8, 2)->default(0);
            $t->integer('tempo_de_aula')->default(60); $t->string('imagem')->default(''); $t->string('tipo_agendamento')->default('HORARIO'); $t->unsignedBigInteger('categoria_id')->nullable(); $t->timestamps();
        });
        Schema::create('bot_services', function ($t) {
            $t->id(); $t->unsignedBigInteger('bot_id'); $t->unsignedBigInteger('servico_id'); $t->timestamps();
        });
        Schema::create('user_events', function ($t) {
            $t->id(); $t->unsignedBigInteger('user_id')->nullable(); $t->string('event_type'); $t->text('payload')->nullable(); $t->string('ip')->nullable();
            $t->string('user_agent')->nullable(); $t->string('source')->nullable(); $t->timestamps();
        });

        $this->donoA = Usuario::create(['nome' => 'Dono A', 'email' => 'a@teste.local', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $donoB = Usuario::create(['nome' => 'Dono B', 'email' => 'b@teste.local', 'password' => 'x', 'tipo_usuario' => 'Professor']);
        $this->a = Empresa::create(['user_id' => $this->donoA->id, 'modalidade_id' => 1, 'nome' => 'A', 'descricao' => '', 'telefone' => '', 'cnpj' => '', 'valor_aula_de' => 0, 'valor_aula_ate' => 0]);
        $this->b = Empresa::create(['user_id' => $donoB->id, 'modalidade_id' => 1, 'nome' => 'B', 'descricao' => '', 'telefone' => '', 'cnpj' => '', 'valor_aula_de' => 0, 'valor_aula_ate' => 0]);
        $this->botA = Bot::create(['empresa_id' => $this->a->id, 'nome' => 'Bot A', 'status' => '1']);
        $this->botB = Bot::create(['empresa_id' => $this->b->id, 'nome' => 'Bot B', 'status' => '1']);
        $this->servA = Servicos::create(['empresa_id' => $this->a->id, 'titulo' => 'Serv A', 'preco' => 0, 'tempo_de_aula' => 60, 'imagem' => '', 'tipo_agendamento' => 'HORARIO']);
        $this->servB = Servicos::create(['empresa_id' => $this->b->id, 'titulo' => 'Serv B', 'preco' => 0, 'tempo_de_aula' => 60, 'imagem' => '', 'tipo_agendamento' => 'HORARIO']);

        $this->actingAs($this->donoA);
    }

    private function bots(): BotController
    {
        return app(BotController::class);
    }

    public function test_lista_mostra_so_os_bots_da_propria_empresa(): void
    {
        $lista = $this->bots()->index()->getData()['bots'];

        $this->assertSame(['Bot A'], $lista->pluck('nome')->all());
    }

    public function test_bot_de_outra_empresa_vira_404_em_show_edit_update_e_destroy(): void
    {
        foreach ([
            fn () => $this->bots()->show($this->botB->id),
            fn () => $this->bots()->edit($this->botB->id),
            fn () => $this->bots()->update(Request::create('/', 'PUT', ['nome' => 'invadido']), $this->botB->id),
            fn () => $this->bots()->destroy(Request::create('/', 'DELETE', ['id' => $this->botB->id])),
        ] as $acao) {
            try {
                $acao();
                $this->fail('deveria ter dado 404');
            } catch (NotFoundHttpException|\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $this->assertTrue(true);
            }
        }

        $this->assertSame('Bot B', Bot::find($this->botB->id)->nome, 'o bot alheio não pode ser alterado nem apagado');
    }

    public function test_chat_com_bot_de_outra_empresa_vira_404(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->bots()->chat(Request::create('/', 'POST', ['message' => 'oi', 'empresa_id' => $this->b->id]), $this->botB);
    }

    public function test_criar_bot_ignora_empresa_id_do_formulario(): void
    {
        $this->bots()->store(Request::create('/', 'POST', ['nome' => 'Novo', 'empresa_id' => $this->b->id, 'status' => '1']));

        $novo = Bot::where('nome', 'Novo')->firstOrFail();
        $this->assertSame($this->a->id, $novo->empresa_id);
    }

    public function test_atualizar_so_liga_servicos_da_propria_empresa(): void
    {
        $this->bots()->update(Request::create('/', 'PUT', ['nome' => 'Bot A', 'services' => [$this->servA->id, $this->servB->id]]), $this->botA->id);

        $this->assertSame([$this->servA->id], $this->botA->services()->pluck('servicos.id')->all());
    }

    public function test_vinculo_bot_servico_de_outra_empresa_vira_404(): void
    {
        $vinculoB = BotService::create(['bot_id' => $this->botB->id, 'servico_id' => $this->servB->id]);
        $ctrl = app(BotServiceController::class);

        $this->assertCount(0, $ctrl->index()->getData()['botServices']);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $ctrl->edit($vinculoB->id);
    }
}
