<?php

namespace App\Http\Middleware;

use App\Models\AlunoProfessor;
use App\Models\Agendamento;
use App\Models\Alunos;
use App\Models\Aulas;
use App\Models\Disponibilidade;
use App\Models\Empresa;
use App\Models\EmpresaGaleria;
use App\Models\PagamentoGateway;
use App\Models\Professor;
use App\Models\Servicos;
use App\Models\Usuario;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Impede que um usuario logado leia/altere dados de OUTRA empresa pela API do painel.
 *
 * Uso: ->middleware('dono:servicos'). Quem e "master" (config/master_users.php) passa sempre.
 * O tipo "master" libera so o master (recursos globais: modalidades, permissoes, ferramentas de teste).
 * Os demais tipos conferem o registro da rota (ex.: servicos/{servico}) e, em qualquer tipo, os ids que
 * vem no corpo da requisicao (empresa_id, professor_id, user_id, aluno_id, servico_id).
 */
class ApenasDaPropriaEmpresa
{
    /** @var int[] */
    private array $empresas = [];

    private ?Usuario $usuario = null;

    public function handle(Request $request, Closure $next, string $tipo, string ...$args)
    {
        $usuario = $request->user();
        abort_unless($usuario, 401, 'Nao autenticado.');

        if ($usuario->isMasterUser()) {
            return $next($request);
        }

        abort_if($tipo === 'master', 403, 'Acesso restrito ao administrador.');

        $this->usuario = $usuario;
        $this->empresas = $this->empresasDoUsuario($usuario);

        $permitido = $this->registroDaRota($request, $tipo, $args) && $this->corpoDaRequisicao($request);

        abort_unless($permitido, 403, 'Voce so pode acessar dados da sua propria empresa.');

        return $next($request);
    }

    /** Empresas que o usuario possui + a empresa em que ele e professor. */
    private function empresasDoUsuario(Usuario $usuario): array
    {
        $ids = Empresa::where('user_id', $usuario->id)->pluck('id')->all();

        if ($empresaDoProfessor = Professor::where('usuario_id', $usuario->id)->value('empresa_id')) {
            $ids[] = (int) $empresaDoProfessor;
        }

        return array_values(array_unique(array_map('intval', $ids)));
    }

    private function minha(?int $empresaId): bool
    {
        return $empresaId !== null && in_array($empresaId, $this->empresas, true);
    }

    private function id(mixed $valor): ?int
    {
        if ($valor instanceof Model) {
            $valor = $valor->getKey();
        }

        return is_numeric($valor) ? (int) $valor : null;
    }

    private function empresaDoProfessor(?int $professorId): ?int
    {
        return $professorId ? (Professor::where('id', $professorId)->value('empresa_id') ?: null) : null;
    }

    private function alunoEhDaMinhaEmpresa(?int $alunoId): bool
    {
        if (! $alunoId) {
            return false;
        }
        // o proprio aluno
        if (Alunos::where('id', $alunoId)->where('usuario_id', $this->usuario->id)->exists()) {
            return true;
        }
        $professores = AlunoProfessor::where('aluno_id', $alunoId)->pluck('professor_id');

        return $professores->contains(fn ($p) => $this->minha($this->empresaDoProfessor((int) $p)));
    }

    private function usuarioEhDaMinhaEmpresa(?int $usuarioId): bool
    {
        if (! $usuarioId) {
            return false;
        }
        if ($usuarioId === (int) $this->usuario->id) {
            return true;
        }
        if ($this->minha(Professor::where('usuario_id', $usuarioId)->value('empresa_id') ?: null)) {
            return true;
        }
        $aluno = Alunos::where('usuario_id', $usuarioId)->value('id');

        return $aluno ? $this->alunoEhDaMinhaEmpresa((int) $aluno) : false;
    }

    /** Confere o registro que a rota esta alterando. Registro inexistente passa (o controller responde 404). */
    private function registroDaRota(Request $request, string $tipo, array $args = []): bool
    {
        $route = $request->route();
        $parametros = $route ? array_values($route->parameters()) : [];
        $id = $this->id($parametros[0] ?? null);

        return match ($tipo) {
            'servicos' => $id === null || $this->dono(Servicos::where('id', $id)->value('empresa_id')),
            'empresas' => $id === null || $this->minha($id) || ! Empresa::where('id', $id)->exists(),
            'professor' => $id === null || $this->dono(Professor::where('id', $id)->value('empresa_id')),
            'aulas' => $id === null || $this->dono($this->empresaDoProfessor(Aulas::where('id', $id)->value('professor_id'))),
            'disponibilidade' => $id === null || $this->dono($this->empresaDoProfessor(Disponibilidade::where('id', $id)->value('id_professor'))),
            'agendamento' => $id === null || $this->dono($this->empresaDoProfessor(Agendamento::where('id', $id)->value('professor_id'))),
            'gallery' => $id === null || $this->dono(EmpresaGaleria::where('id', $id)->value('empresa_id')),
            'pagamento' => $id === null || $this->dono(PagamentoGateway::where('id', $id)->value('empresa_id')),
            'users' => $id !== null && $this->usuarioEhDaMinhaEmpresa($id), // listar todos os usuarios (sem id) e so do master
            'usuarios' => $id === null || $this->usuarioEhDaMinhaEmpresa($id), // telas web: lista/criacao (sem id) liberadas
            'direto' => $id === null || $this->dono($this->empresaDoRegistro($args[0] ?? '', $id)),
            'viasite' => $id === null || $this->dono($this->empresaDoSite(DB::table($args[0] ?? '')->where('id', $id)->value('site_id'))),
            'viabot' => $id === null || $this->dono(DB::table('bots')->where('id', DB::table('bot_services')->where('id', $id)->value('bot_id'))->value('empresa_id')),
            'fotosite' => $id === null || $this->dono($this->empresaDoSite(DB::table('site_servicos')->where('id', DB::table('site_servico_fotos')->where('id', $id)->value('site_servico_id'))->value('site_id'))),
            'aluno' => $this->alunoDaRota($id, $parametros),
            'corpo' => true,
            default => false, // tipo desconhecido: nega
        };
    }

    /** Tabela com coluna empresa_id (nome vem das regras fixas em RegrasDeEmpresa, nunca da requisicao). */
    private function empresaDoRegistro(string $tabela, int $id): ?int
    {
        $v = DB::table($tabela)->where('id', $id)->value('empresa_id');

        return $v ? (int) $v : null;
    }

    private function empresaDoSite(mixed $siteId): ?int
    {
        $v = $siteId ? DB::table('empresa_site')->where('id', $siteId)->value('empresa_id') : null;

        return $v ? (int) $v : null;
    }

    /** aluno/{id}/destroy/{professor_id}: o aluno e o professor precisam ser da minha empresa. */
    private function alunoDaRota(?int $alunoId, array $parametros): bool
    {
        if ($alunoId !== null && ! Alunos::where('id', $alunoId)->exists()) {
            return true;
        }
        if (isset($parametros[1]) && ! $this->dono($this->empresaDoProfessor($this->id($parametros[1])) ?: null)) {
            return false;
        }

        return $alunoId === null || $this->alunoEhDaMinhaEmpresa($alunoId);
    }

    /** Registro sem empresa (null) nao pertence a ninguem: nega. */
    private function dono(?int $empresaId): bool
    {
        return $this->minha($empresaId);
    }

    /** Ids enviados no corpo/query da requisicao precisam ser da minha empresa. */
    private function corpoDaRequisicao(Request $request): bool
    {
        if ($request->filled('empresa_id') && ! $this->minha($this->id($request->input('empresa_id')))) {
            return false;
        }

        foreach (['professor_id', 'id_professor'] as $campo) {
            if ($request->filled($campo) && ! $this->minha($this->empresaDoProfessor($this->id($request->input($campo))))) {
                return false;
            }
        }

        if ($request->filled('servico_id') && ! $this->minha(Servicos::where('id', $this->id($request->input('servico_id')))->value('empresa_id') ?: null)) {
            return false;
        }

        if ($request->filled('user_id') && ! $this->usuarioEhDaMinhaEmpresa($this->id($request->input('user_id')))) {
            return false;
        }

        if ($request->filled('aluno_id') && ! $this->alunoEhDaMinhaEmpresa($this->id($request->input('aluno_id')))) {
            return false;
        }

        return true;
    }
}
