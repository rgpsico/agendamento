<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendamentoChatController extends Controller
{
    public function index()
    {
        return view('admin.agendamentos.chat', [
            'pageTitle' => 'Chat IA · Agendamentos',
        ]);
    }

    public function query(Request $request)
    {
        $texto = mb_strtolower($request->input('mensagem', ''));
        $user  = Auth::user();

        // ── Resolve empresa_id ─────────────────────────────────────────────
        $empresaId = null;
        if ($user->professor) {
            $empresaId = $user->professor->empresa_id;
        } elseif ($user->empresa) {
            $empresaId = $user->empresa->id;
        }

        // ── Base query com relacionamentos ─────────────────────────────────
        $q = Agendamento::with(['aluno.usuario', 'professor.usuario', 'modalidade', 'servico']);

        if ($user->professor) {
            $q->where('professor_id', $user->professor->id);
        } elseif ($empresaId) {
            $q->whereHas('professor', fn($p) => $p->where('empresa_id', $empresaId));
        }

        // ── Interpreta intenção ────────────────────────────────────────────
        $label     = '';
        $tipo      = 'agendamentos'; // agendamentos | clientes | financeiro | insights

        // HOJE
        if (preg_match('/\bhoje\b/', $texto)) {
            $q->whereDate('data_da_aula', today());
            $label = 'Agendamentos de hoje — ' . today()->translatedFormat('d \d\e F');
        }
        // SEMANA
        elseif (preg_match('/\bsemana\b/', $texto)) {
            $q->whereBetween('data_da_aula', [now()->startOfWeek(), now()->endOfWeek()]);
            $label = 'Agendamentos desta semana';
        }
        // MÊS específico (ex: "março", "abril")
        elseif (preg_match('/\b(janeiro|fevereiro|março|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro)\b/', $texto, $m)) {
            $meses = ['janeiro'=>1,'fevereiro'=>2,'março'=>3,'abril'=>4,'maio'=>5,'junho'=>6,
                      'julho'=>7,'agosto'=>8,'setembro'=>9,'outubro'=>10,'novembro'=>11,'dezembro'=>12];
            $num   = $meses[$m[1]];
            $ano   = now()->year;
            $q->whereMonth('data_da_aula', $num)->whereYear('data_da_aula', $ano);
            $label = "Agendamentos de {$m[1]} / {$ano}";
        }
        // PRÓXIMO MÊS
        elseif (preg_match('/próximo\s+mês|proximo\s+mes/', $texto)) {
            $prox = now()->addMonth();
            $q->whereMonth('data_da_aula', $prox->month)->whereYear('data_da_aula', $prox->year);
            $label = 'Agendamentos do próximo mês — ' . $prox->translatedFormat('F Y');
        }
        // MÊS PASSADO
        elseif (preg_match('/mês\s+passado|mes\s+passado|último\s+mês|ultimo\s+mes/', $texto)) {
            $ant = now()->subMonth();
            $q->whereMonth('data_da_aula', $ant->month)->whereYear('data_da_aula', $ant->year);
            $label = 'Agendamentos do mês passado — ' . $ant->translatedFormat('F Y');
        }
        // ESTE MÊS (padrão quando não especificado)
        else {
            $q->whereMonth('data_da_aula', now()->month)->whereYear('data_da_aula', now()->year);
            $label = 'Agendamentos deste mês — ' . now()->translatedFormat('F Y');
        }

        // Filtros adicionais de status / tipo
        if (preg_match('/cancel/', $texto)) {
            $q->where('status', 'cancelado');
            $label = 'Cancelamentos · ' . $label;
        } elseif (preg_match('/confirm/', $texto)) {
            $q->where('status', 'confirmado');
        } elseif (preg_match('/pendente/', $texto)) {
            $q->where('status', 'pendente');
        }

        // Clientes novos
        if (preg_match('/cliente.*novo|novo.*cliente/', $texto)) {
            $tipo = 'clientes';
        }

        // Relatório financeiro
        if (preg_match('/financeiro|receita|faturamento|dinheiro|pagamento/', $texto)) {
            $tipo = 'financeiro';
        }

        $agendamentos = $q->orderBy('data_da_aula', 'asc')->orderBy('horario', 'asc')->get();

        // ── Monta resposta ─────────────────────────────────────────────────
        $rows = $agendamentos->map(function ($ag) {
            $nomeAluno = optional(optional($ag->aluno)->usuario)->name
                      ?? optional($ag->aluno)->nome
                      ?? '—';
            $telefone  = optional(optional($ag->aluno)->usuario)->phone
                      ?? optional($ag->aluno)->telefone
                      ?? '—';
            return [
                'id'        => $ag->id,
                'cliente'   => $nomeAluno,
                'telefone'  => $telefone,
                'data'      => $ag->data_da_aula ? Carbon::parse($ag->data_da_aula)->translatedFormat('d/m/Y') : '—',
                'dia'       => $ag->data_da_aula ? Carbon::parse($ag->data_da_aula)->translatedFormat('l') : '',
                'horario'   => $ag->horario ?? '—',
                'servico'   => optional($ag->servico)->nome ?? optional($ag->modalidade)->nome ?? '—',
                'status'    => $ag->status ?? 'agendado',
                'valor'     => $ag->valor_aula ? 'R$ ' . number_format($ag->valor_aula, 2, ',', '.') : '—',
                'professor' => optional(optional($ag->professor)->usuario)->name ?? '—',
            ];
        });

        // ── Insights rápidos ───────────────────────────────────────────────
        $insights = $this->buildInsights($empresaId, $user);

        return response()->json([
            'label'       => $label,
            'total'       => $rows->count(),
            'tipo'        => $tipo,
            'agendamentos'=> $rows,
            'insights'    => $insights,
            'sugestoes'   => $this->sugestoes($texto),
        ]);
    }

    private function buildInsights(?int $empresaId, $user): array
    {
        $base = Agendamento::query();

        if ($user->professor) {
            $base->where('professor_id', $user->professor->id);
        } elseif ($empresaId) {
            $base->whereHas('professor', fn($p) => $p->where('empresa_id', $empresaId));
        }

        $mesAtual  = (clone $base)->whereMonth('data_da_aula', now()->month)->whereYear('data_da_aula', now()->year);
        $hoje      = (clone $base)->whereDate('data_da_aula', today());

        $totalMes       = (clone $mesAtual)->count();
        $totalHoje      = (clone $hoje)->count();
        $cancelamentos  = (clone $mesAtual)->where('status', 'cancelado')->count();
        $receitaPrevista= (clone $mesAtual)->sum('valor_aula');

        // Horários mais movimentados
        $horarios = (clone $mesAtual)
            ->select('horario', DB::raw('count(*) as total'))
            ->groupBy('horario')
            ->orderByDesc('total')
            ->limit(3)
            ->pluck('total', 'horario')
            ->toArray();

        // Clientes novos no mês (alunos únicos que agendaram este mês)
        $clientesNovos = (clone $mesAtual)->distinct('aluno_id')->count('aluno_id');

        return [
            'agendamentos_mes'    => $totalMes,
            'agendamentos_hoje'   => $totalHoje,
            'cancelamentos'       => $cancelamentos,
            'clientes_novos'      => $clientesNovos,
            'receita_prevista'    => 'R$ ' . number_format($receitaPrevista, 2, ',', '.'),
            'horarios_top'        => $horarios,
        ];
    }

    private function sugestoes(string $texto): array
    {
        $todas = [
            'Agendamentos de hoje',
            'Agendamentos deste mês',
            'Clientes novos',
            'Cancelamentos do mês',
            'Agendamentos da semana',
            'Relatório financeiro',
            'Agendamentos confirmados',
            'Agendamentos pendentes',
        ];
        // Remove a sugestão que seja parecida com o que já foi perguntado
        return array_values(array_filter($todas, fn($s) => !str_contains(mb_strtolower($s), substr($texto, 0, 8))));
    }
}
