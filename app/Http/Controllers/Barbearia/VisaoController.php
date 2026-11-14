<?php

namespace App\Http\Controllers\Barbearia;

use App\Http\Controllers\Controller;
use App\Models\Alunos;
use App\Models\Empresa;
use App\Models\FilaAtendimento;
use App\Models\NichoConfiguracao;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/** Painel do dono: visão geral da barbearia ao vivo (todas as filas, números do dia e dos últimos 7 dias). */
class VisaoController extends Controller
{
    public function index(Request $request)
    {
        $empresa = $this->empresa($request);
        $dados = $this->dados($empresa);

        if ($request->ajax() || $request->boolean('fragmento')) {
            return view('barbearia._visao', $dados);
        }

        return view('barbearia.visao', $dados + [
            'nicho' => NichoConfiguracao::daEmpresa($empresa),
            'empresa' => $empresa,
            'barbearias' => $this->barbearias($request),
            'souBarbeiro' => (bool) BarbeiroController::doUsuario($request->user()),
        ]);
    }

    private function dados(Empresa $empresa): array
    {
        $hoje = now()->startOfDay();

        $barbeiros = Professor::where('empresa_id', $empresa->id)->where('ativo', true)->with('usuario')->orderBy('id')->get();

        // fila aberta (esperando + atendendo) e tudo que terminou hoje: duas consultas para a tela inteira
        $abertas = FilaAtendimento::abertas()->where('empresa_id', $empresa->id)->orderBy('chegou_em')->orderBy('id')->get();
        $encerradosHoje = FilaAtendimento::where('empresa_id', $empresa->id)->where('finalizado_em', '>=', $hoje)
            ->whereIn('status', [FilaAtendimento::CONCLUIDO, FilaAtendimento::AUSENTE, FilaAtendimento::DESISTIU])->get();

        $concluidos = $encerradosHoje->where('status', FilaAtendimento::CONCLUIDO);
        $esperaMedia = $this->media($concluidos->filter(fn ($f) => $f->iniciado_em)->map(fn ($f) => $f->chegou_em->diffInMinutes($f->iniciado_em)));
        $duracaoMedia = $this->media($concluidos->filter(fn ($f) => $f->iniciado_em)->map(fn ($f) => $f->iniciado_em->diffInMinutes($f->finalizado_em)));

        $clientes = Alunos::whereIn('id', $abertas->pluck('aluno_id')->unique())->with('usuario')->get()->keyBy('id');

        $porBarbeiro = $barbeiros->map(function (Professor $b) use ($abertas, $concluidos, $clientes) {
            $dele = $abertas->where('professor_id', $b->id);
            $atendendo = $dele->firstWhere('status', FilaAtendimento::ATENDENDO);

            return (object) [
                'barbeiro' => $b,
                'atendendo' => $atendendo,
                'cliente_atendendo' => $atendendo ? optional(optional($clientes->get($atendendo->aluno_id))->usuario)->nome : null,
                'esperando' => $dele->where('status', FilaAtendimento::AGUARDANDO)->count(),
                'atendidos_hoje' => $concluidos->where('professor_id', $b->id)->count(),
            ];
        });

        // últimos 7 dias (hoje incluído): atendimentos concluídos por dia
        $inicio = now()->subDays(6)->startOfDay();
        $porDia = FilaAtendimento::where('empresa_id', $empresa->id)->where('status', FilaAtendimento::CONCLUIDO)->where('finalizado_em', '>=', $inicio)
            ->pluck('finalizado_em')->groupBy(fn ($d) => $d->toDateString())->map->count();
        $dias = collect(range(6, 0))->map(function ($atras) use ($porDia) {
            $d = now()->subDays($atras);

            return (object) ['data' => $d, 'total' => (int) ($porDia[$d->toDateString()] ?? 0)];
        });

        return [
            'esperando' => $abertas->where('status', FilaAtendimento::AGUARDANDO)->values(),
            'emAtendimento' => $abertas->where('status', FilaAtendimento::ATENDENDO)->count(),
            'atendidosHoje' => $concluidos->count(),
            'ausentesHoje' => $encerradosHoje->where('status', FilaAtendimento::AUSENTE)->count(),
            'desistiramHoje' => $encerradosHoje->where('status', FilaAtendimento::DESISTIU)->count(),
            'esperaMedia' => $esperaMedia,
            'duracaoMedia' => $duracaoMedia,
            'porBarbeiro' => $porBarbeiro,
            'nomes' => $barbeiros->mapWithKeys(fn ($b) => [$b->id => optional($b->usuario)->nome]),
            'clientes' => $clientes,
            'dias' => $dias,
            'maxDia' => max(1, $dias->max('total')),
            'totalSemana' => $dias->sum('total'),
        ];
    }

    private function media($minutos): ?int
    {
        return $minutos->isEmpty() ? null : (int) round($minutos->avg());
    }

    private function barbearias(Request $request)
    {
        return Empresa::where('user_id', $request->user()->id)->where('status', 'ativo')->get()
            ->filter(fn ($e) => optional(NichoConfiguracao::daEmpresa($e))->recurso('fila_checkin'))->values();
    }

    private function empresa(Request $request): Empresa
    {
        $minhas = $this->barbearias($request);
        abort_if($minhas->isEmpty(), 403, 'Você não tem uma barbearia.');

        return $minhas->firstWhere('id', (int) $request->input('empresa')) ?? $minhas->first();
    }
}
