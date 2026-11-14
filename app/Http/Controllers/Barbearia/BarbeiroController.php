<?php

namespace App\Http\Controllers\Barbearia;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Alunos;
use App\Models\FilaAtendimento;
use App\Models\NichoConfiguracao;
use App\Models\Professor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Tela do barbeiro: fila ao vivo (chamar, iniciar, finalizar) e agenda do dia. */
class BarbeiroController extends Controller
{
    /** O barbeiro (ativo, de uma barbearia) ligado a este usuário, ou null. */
    public static function doUsuario(Usuario $usuario): ?Professor
    {
        return Professor::where('usuario_id', $usuario->id)->where('ativo', true)->with('empresa')->get()
            ->first(fn ($p) => $p->empresa && $p->empresa->status === 'ativo'
                && optional(NichoConfiguracao::daEmpresa($p->empresa))->recurso('fila_checkin'));
    }

    public function fila(Request $request)
    {
        $barbeiro = $this->barbeiro($request);
        $dados = $this->dados($barbeiro);

        if ($request->ajax() || $request->boolean('fragmento')) {
            return view('barbearia._fila_barbeiro', $dados);
        }

        return view('barbearia.fila_barbeiro', $dados + ['nicho' => NichoConfiguracao::daEmpresa($barbeiro->empresa)]);
    }

    public function chamar(Request $request, FilaAtendimento $fila)
    {
        return $this->acao($request, $fila, function (FilaAtendimento $f) {
            if ($f->status !== FilaAtendimento::AGUARDANDO) {
                return ['erro', 'Este cliente não está mais esperando.'];
            }
            $f->update(['chamado_em' => now()]);

            return ['ok', 'Cliente chamado: ele vê o aviso no celular.'];
        });
    }

    /** Recado do barbeiro para o cliente que espera ("onde você está?"); o cliente vê na tela da fila e pode responder. */
    public function mensagem(Request $request, FilaAtendimento $fila)
    {
        $dados = $request->validate(['texto' => ['nullable', 'string', 'max:140']]);
        $texto = trim((string) ($dados['texto'] ?? '')) ?: 'Onde você está? Já vai ser a sua vez.';

        return $this->acao($request, $fila, function (FilaAtendimento $f) use ($texto) {
            if ($f->status !== FilaAtendimento::AGUARDANDO) {
                return ['erro', 'Este cliente não está mais esperando.'];
            }
            // nova mensagem limpa a resposta antiga: a resposta que aparece é sempre a da última pergunta
            $f->update(['mensagem' => $texto, 'mensagem_em' => now(), 'resposta' => null, 'resposta_em' => null]);

            return ['ok', 'Mensagem enviada: o cliente vê no celular.'];
        });
    }

    public function iniciar(Request $request, FilaAtendimento $fila)
    {
        return $this->acao($request, $fila, function (FilaAtendimento $f) {
            if ($f->status !== FilaAtendimento::AGUARDANDO) {
                return ['erro', 'Este cliente não está mais esperando.'];
            }
            if (FilaAtendimento::where('professor_id', $f->professor_id)->where('status', FilaAtendimento::ATENDENDO)->exists()) {
                return ['erro', 'Finalize o atendimento em andamento antes de iniciar outro.'];
            }
            $f->update(['status' => FilaAtendimento::ATENDENDO, 'chamado_em' => $f->chamado_em ?? now(), 'iniciado_em' => now()]);

            return ['ok', 'Atendimento iniciado.'];
        });
    }

    public function finalizar(Request $request, FilaAtendimento $fila)
    {
        return $this->acao($request, $fila, function (FilaAtendimento $f) {
            if ($f->status !== FilaAtendimento::ATENDENDO) {
                return ['erro', 'Este atendimento não está em andamento.'];
            }
            $f->update(['status' => FilaAtendimento::CONCLUIDO, 'finalizado_em' => now()]);

            return ['ok', 'Atendimento concluído.'];
        });
    }

    public function ausente(Request $request, FilaAtendimento $fila)
    {
        return $this->acao($request, $fila, function (FilaAtendimento $f) {
            if ($f->status !== FilaAtendimento::AGUARDANDO) {
                return ['erro', 'Este cliente não está mais esperando.'];
            }
            $f->update(['status' => FilaAtendimento::AUSENTE, 'finalizado_em' => now()]);

            return ['ok', 'Cliente retirado da fila (não apareceu).'];
        });
    }

    /** Só o próprio barbeiro mexe na fila dele; a ação roda com a linha travada para dois toques não conflitarem. */
    private function acao(Request $request, FilaAtendimento $fila, \Closure $faz)
    {
        $barbeiro = $this->barbeiro($request);
        abort_unless($fila->professor_id === $barbeiro->id, 403);

        [$tipo, $mensagem] = DB::transaction(function () use ($fila, $faz) {
            return $faz(FilaAtendimento::whereKey($fila->id)->lockForUpdate()->first());
        });

        return redirect()->route('barbearia.painel.fila')->with($tipo, $mensagem);
    }

    private function barbeiro(Request $request): Professor
    {
        $barbeiro = self::doUsuario($request->user());
        abort_unless($barbeiro, 403, 'Você não é barbeiro de uma barbearia.');

        return $barbeiro;
    }

    private function dados(Professor $barbeiro): array
    {
        $fila = FilaAtendimento::abertas()->where('professor_id', $barbeiro->id)
            ->orderByRaw("status = 'atendendo' desc")->orderBy('chegou_em')->orderBy('id')->get();

        $agenda = Agendamento::where('professor_id', $barbeiro->id)->whereDate('data_da_aula', now()->toDateString())
            ->whereNotIn('status', ['Cancelado', 'cancelado', 'Cancelada'])->with('servico')->orderBy('horario')->get();

        $clientes = Alunos::whereIn('id', $fila->pluck('aluno_id')->merge($agenda->pluck('aluno_id'))->unique())->with('usuario')->get()->keyBy('id');
        $horarioHoje = $agenda->groupBy('aluno_id')->map(fn ($g) => substr((string) $g->first()->horario, 0, 5));

        return [
            'barbeiro' => $barbeiro,
            'fila' => $fila,
            'agenda' => $agenda,
            'clientes' => $clientes,
            'horarioHoje' => $horarioHoje,
            'naFilaIds' => $fila->pluck('aluno_id')->all(),
            'atendidosHoje' => FilaAtendimento::where('professor_id', $barbeiro->id)->where('status', FilaAtendimento::CONCLUIDO)->whereDate('finalizado_em', now()->toDateString())->count(),
        ];
    }
}
