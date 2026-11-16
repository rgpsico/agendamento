<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receita;
use App\Models\ReceitaRecorrente;
use App\Models\DespesaRecorrente;
use App\Models\Despesas;

class FinanceiroDashboardController extends Controller
{

    public function index()
    {
        $request = request();
        $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->get('data_fim', now()->endOfMonth()->format('Y-m-d'));
        $tipos = (array) $request->get('tipo', ['todos']);

        if (empty($tipos)) {
            $tipos = ['todos'];
        }


        // Inicializa totais
        $totalReceitas = $totalDespesas = 0;
        $totalReceitasRecorrentes = $totalDespesasRecorrentes = 0;
        $receitasPendentes = $receitasRecebidas = 0;
        $totalReceitasPendentes = $totalReceitasRecebidas = 0;
        $despesasPendentes = 0;
        $totalDespesasPendentes = 0;

        // Receitas normais
        if (in_array('todos', $tipos) || in_array('receitas', $tipos)) {
            $totalReceitas = Receita::noPeriodo($dataInicio, $dataFim)->sum('valor');
            $receitasPendentes = Receita::where('status', 'PENDENTE')->noPeriodo($dataInicio, $dataFim)->count();
            $receitasRecebidas = Receita::where('status', 'RECEBIDA')->noPeriodo($dataInicio, $dataFim)->count();
            $totalReceitasPendentes = Receita::where('status', 'PENDENTE')->noPeriodo($dataInicio, $dataFim)->sum('valor');
            $totalReceitasRecebidas = Receita::where('status', 'RECEBIDA')->noPeriodo($dataInicio, $dataFim)->sum('valor');
        }

        // Despesas normais
        if (in_array('todos', $tipos) || in_array('despesas', $tipos)) {
            $totalDespesas = Despesas::noPeriodo($dataInicio, $dataFim)->sum('valor');
            $despesasPendentes = Despesas::whereIn('status', ['PENDING', 'PENDENTE'])->noPeriodo($dataInicio, $dataFim)->count();
            $totalDespesasPendentes = Despesas::whereIn('status', ['PENDING', 'PENDENTE'])->noPeriodo($dataInicio, $dataFim)->sum('valor');
        }

        // Receitas Recorrentes
        if (in_array('todos', $tipos) || in_array('receitas_recorrentes', $tipos)) {
            // valor × quantas vezes acontece no período (aluguel mensal em 3 meses = 3×)
            $totalReceitasRecorrentes = ReceitaRecorrente::noPeriodo($dataInicio, $dataFim)->get()
                ->sum(fn ($r) => $r->valorNoPeriodo($dataInicio, $dataFim));
        }

        // Despesas Recorrentes
        if (in_array('todos', $tipos) || in_array('despesas_recorrentes', $tipos)) {
            $totalDespesasRecorrentes = DespesaRecorrente::noPeriodo($dataInicio, $dataFim)->get()
                ->sum(fn ($d) => $d->valorNoPeriodo($dataInicio, $dataFim));
        }

        $receitasMes = 0;
        $despesasMes = 0;

        if (in_array('todos', $tipos) || in_array('receitas', $tipos)) {
            $receitasMes += $totalReceitas;
        }

        if (in_array('todos', $tipos) || in_array('receitas_recorrentes', $tipos)) {
            $receitasMes += $totalReceitasRecorrentes;
        }

        if (in_array('todos', $tipos) || in_array('despesas', $tipos)) {
            $despesasMes += $totalDespesas;
        }

        if (in_array('todos', $tipos) || in_array('despesas_recorrentes', $tipos)) {
            $despesasMes += $totalDespesasRecorrentes;
        }

        $resultadoMes = $receitasMes - $despesasMes;

        $faturamentoPorCategoria = $this->faturamentoPorCategoria($dataInicio, $dataFim);

        $receitasDetalhadas = collect();

        // Receitas normais
        if (in_array('todos', $tipos) || in_array('receitas', $tipos)) {
            $receitas = Receita::with(['usuario', 'categoria', 'pagamento'])
                ->noPeriodo($dataInicio, $dataFim);

            if ($request->get('status') === 'RECEBIDA') {
                $receitas->where('status', 'RECEBIDA');
            } elseif ($request->get('status') === 'PENDENTE') {
                $receitas->where('status', 'PENDENTE');
            }

            $receitasDetalhadas = $receitasDetalhadas->merge($receitas->get());
        }

        // Receitas recorrentes
        if (in_array('todos', $tipos) || in_array('receitas_recorrentes', $tipos)) {
            $receitasRecorrentes = ReceitaRecorrente::with(['usuario', 'categoria'])
                ->noPeriodo($dataInicio, $dataFim);

            if ($request->get('status') === 'RECEBIDA') {
                $receitasRecorrentes->where('status', 'ACTIVE'); // ajuste se tiver outro status
            } elseif ($request->get('status') === 'PENDENTE') {
                $receitasRecorrentes->where('status', 'INACTIVE'); // ajuste se tiver outro status
            }

            $receitasDetalhadas = $receitasDetalhadas->merge($receitasRecorrentes->get());
        }



        $despesasDetalhadas = collect();

        // Despesas normais
        if (in_array('todos', $tipos) || in_array('despesas', $tipos)) {
            $despesas = Despesas::with(['usuario', 'categoria'])
                ->noPeriodo($dataInicio, $dataFim);

            // despesas são gravadas como PENDING / PAID
            if ($request->get('status') === 'PENDENTE') {
                $despesas->whereIn('status', ['PENDING', 'PENDENTE']);
            } elseif ($request->get('status') === 'RECEBIDA') {
                $despesas->whereIn('status', ['PAID', 'PAGA', 'PAGO']);
            }

            $despesasDetalhadas = $despesasDetalhadas->merge($despesas->get());
        }

        // Despesas recorrentes
        if (in_array('todos', $tipos) || in_array('despesas_recorrentes', $tipos)) {
            $despesasRecorrentes = DespesaRecorrente::with(['usuario', 'categoria'])
                ->noPeriodo($dataInicio, $dataFim);

            if ($request->get('status') === 'ACTIVE') {
                $despesasRecorrentes->where('status', 'ACTIVE'); // ajuste conforme sua regra
            } elseif ($request->get('status') === 'INACTIVE') {
                $despesasRecorrentes->where('status', 'INACTIVE');
            }

            $despesasDetalhadas = $despesasDetalhadas->merge($despesasRecorrentes->get());
        }

        // Ordenar por data
        $despesasDetalhadas = $despesasDetalhadas->sortByDesc(fn ($d) => $d->data_competencia ?? $d->data_inicio ?? $d->created_at);

        // Ordenar por data
        $receitasDetalhadas = $receitasDetalhadas->sortByDesc(fn ($r) => $r->data_competencia ?? $r->data_inicio ?? $r->created_at);
        $tipoSelecionado = $tipos;

        return view('admin.financeiro.dashboard.dashboard', compact(
            'totalReceitas',
            'totalDespesas',
            'receitasMes',
            'despesasMes',
            'receitasPendentes',
            'receitasRecebidas',
            'totalReceitasPendentes',
            'totalReceitasRecebidas',
            'despesasPendentes',
            'totalDespesasPendentes',
            'totalReceitasRecorrentes',
            'totalDespesasRecorrentes',
            'resultadoMes',
            'dataInicio',
            'dataFim',
            'tipoSelecionado',
            'receitasDetalhadas',
            'despesasDetalhadas',
            'faturamentoPorCategoria'
        ));
    }

    /**
     * Faturamento do período por categoria de receita (ex.: Pilates x Fisioterapia).
     * Soma receitas avulsas/de agendamento e receitas recorrentes ativas no período.
     *
     * @return \Illuminate\Support\Collection<int, array{nome:string, total:float, recebido:float, pendente:float, percentual:float}>
     */
    private function faturamentoPorCategoria(string $dataInicio, string $dataFim)
    {
        $linhas = [];
        $somar = function ($categoriaId, float $total, float $recebido) use (&$linhas) {
            $chave = $categoriaId ?: 0;
            $linhas[$chave] ??= ['total' => 0.0, 'recebido' => 0.0];
            $linhas[$chave]['total'] += $total;
            $linhas[$chave]['recebido'] += $recebido;
        };

        Receita::noPeriodo($dataInicio, $dataFim)
            ->selectRaw("categoria_id, SUM(valor) AS total, SUM(CASE WHEN UPPER(status) = 'RECEBIDA' THEN valor ELSE 0 END) AS recebido")
            ->groupBy('categoria_id')
            ->get()
            ->each(fn ($r) => $somar($r->categoria_id, (float) $r->total, (float) $r->recebido));

        // recorrentes: valor × vezes no período; entram como previstas (a receber)
        ReceitaRecorrente::noPeriodo($dataInicio, $dataFim)->get()
            ->each(fn ($r) => $somar($r->categoria_id, $r->valorNoPeriodo($dataInicio, $dataFim), 0.0));

        $nomes = \App\Models\FinanceiroCategoria::whereIn('id', array_keys($linhas))->pluck('nome', 'id');
        $geral = array_sum(array_column($linhas, 'total')) ?: 1;

        return collect($linhas)
            ->map(fn ($l, $id) => [
                'nome'       => $id ? ($nomes[$id] ?? 'Categoria removida') : 'Sem categoria',
                'total'      => $l['total'],
                'recebido'   => $l['recebido'],
                'pendente'   => $l['total'] - $l['recebido'],
                'percentual' => round($l['total'] / $geral * 100, 1),
            ])
            ->sortByDesc('total')
            ->values();
    }

    /**
     * Relatório do período em Excel (.xlsx) para o contador:
     * Resumo, Receitas, Despesas, Recorrentes e Faturamento por categoria. Só dados da empresa logada.
     */
    public function exportar(Request $request)
    {
        $request->validate([
            'data_inicio' => 'nullable|date',
            'data_fim'    => 'nullable|date|after_or_equal:data_inicio',
        ]);

        $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim    = $request->get('data_fim', now()->endOfMonth()->format('Y-m-d'));


        $statusReceita = fn ($s) => strtoupper((string) $s) === 'RECEBIDA' ? 'Recebida' : 'Pendente';
        $statusDespesa = fn ($s) => in_array(strtoupper((string) $s), ['PAID', 'PAGA', 'PAGO'], true) ? 'Paga' : 'Pendente';
        $data = fn ($d) => $d ? \Carbon\Carbon::parse($d) : null;

        // Receitas
        $receitas = Receita::with(['usuario', 'categoria', 'pagamento'])
            ->noPeriodo($dataInicio, $dataFim)->orderByRaw(Receita::DATA_COMPETENCIA_SQL)->get();

        $linhasReceitas = $receitas->map(fn ($r) => [
            $data($r->data_competencia),
            $r->descricao,
            $r->usuario->nome ?? '',
            $r->categoria->nome ?? 'Sem categoria',
            Receita::METODOS[$r->metodo] ?? ($r->metodo ?? ''),
            $statusReceita($r->status),
            $data($r->data_vencimento),
            $data($r->data_recebimento),
            (float) $r->valor,
        ])->all();
        $linhasReceitas[] = ['TOTAL', '', '', '', '', '', null, null, (float) $receitas->sum('valor')];

        // Despesas (o model formata o valor como texto; aqui usamos o número bruto)
        $despesas = Despesas::with('categoria')
            ->noPeriodo($dataInicio, $dataFim)->orderByRaw(Despesas::DATA_COMPETENCIA_SQL)->get();
        $valorDespesa = fn ($d) => (float) $d->getRawOriginal('valor');

        $linhasDespesas = $despesas->map(fn ($d) => [
            $data($d->data_competencia),
            $d->descricao,
            $d->categoria->nome ?? 'Sem categoria',
            $statusDespesa($d->status),
            $data($d->data_vencimento),
            $valorDespesa($d),
        ])->all();
        $totalDespesas = $despesas->sum($valorDespesa);
        $linhasDespesas[] = ['TOTAL', '', '', '', null, (float) $totalDespesas];

        // Recorrentes ativas no período
        $frequencias = ['DIARIA' => 'Diária', 'SEMANAL' => 'Semanal', 'MENSAL' => 'Mensal', 'ANUAL' => 'Anual'];
        $receitasRec = ReceitaRecorrente::with('categoria')->noPeriodo($dataInicio, $dataFim)->get();
        $despesasRec = DespesaRecorrente::with('categoria')->noPeriodo($dataInicio, $dataFim)->get();
        $linhasRec = [];
        foreach ([['Receita', $receitasRec], ['Despesa', $despesasRec]] as [$tipo, $itens]) {
            foreach ($itens as $i) {
                $linhasRec[] = [
                    $tipo,
                    $i->descricao,
                    $i->categoria->nome ?? 'Sem categoria',
                    $frequencias[str_replace('DIARIO', 'DIARIA', strtoupper((string) $i->frequencia))] ?? $i->frequencia,
                    $data($i->data_inicio),
                    $data($i->data_fim),
                    (float) $i->getRawOriginal('valor'),
                    $i->vezesNoPeriodo($dataInicio, $dataFim),
                    $i->valorNoPeriodo($dataInicio, $dataFim),
                ];
            }
        }

        // Por categoria
        $porCategoria = $this->faturamentoPorCategoria($dataInicio, $dataFim);
        $linhasCategoria = $porCategoria->map(fn ($l) => [
            $l['nome'], $l['total'], $l['recebido'], $l['pendente'], $l['percentual'] / 100,
        ])->all();
        $linhasCategoria[] = ['TOTAL', (float) $porCategoria->sum('total'), (float) $porCategoria->sum('recebido'), (float) $porCategoria->sum('pendente'), null];

        // Resumo
        $totalReceitas  = (float) $receitas->sum('valor');
        $recebido       = (float) $receitas->filter(fn ($r) => strtoupper((string) $r->status) === 'RECEBIDA')->sum('valor');
        $despesasPagas  = (float) $despesas->filter(fn ($d) => $statusDespesa($d->status) === 'Paga')->sum($valorDespesa);
        $totalRecReceitas = (float) $receitasRec->sum(fn ($i) => $i->valorNoPeriodo($dataInicio, $dataFim));
        $totalRecDespesas = (float) $despesasRec->sum(fn ($i) => $i->valorNoPeriodo($dataInicio, $dataFim));
        $empresa = \App\Models\Empresa::find(\App\Support\EmpresasDoUsuario::ids($request->user())[0] ?? 0);

        $resumo = [
            ['Empresa', $empresa->nome ?? ''],
            ['Período', \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') . ' a ' . \Carbon\Carbon::parse($dataFim)->format('d/m/Y')],
            ['Gerado em', now()->format('d/m/Y H:i')],
            ['', ''],
            ['Receitas lançadas', $totalReceitas],
            ['   recebidas', $recebido],
            ['   a receber', $totalReceitas - $recebido],
            ['Receitas recorrentes', $totalRecReceitas],
            ['Despesas lançadas', (float) $totalDespesas],
            ['   pagas', $despesasPagas],
            ['   a pagar', (float) $totalDespesas - $despesasPagas],
            ['Despesas recorrentes', $totalRecDespesas],
            ['', ''],
            ['TOTAL (receitas − despesas)', ($totalReceitas + $totalRecReceitas) - ((float) $totalDespesas + $totalRecDespesas)],
        ];

        $xlsx = (new \App\Support\PlanilhaXlsx())
            ->aba('Resumo', ['Item', 'Valor'], $resumo, ['texto', 'moeda'])
            ->aba('Receitas', ['Data', 'Descrição', 'Aluno', 'Categoria', 'Forma de pagamento', 'Status', 'Vencimento', 'Recebimento', 'Valor'],
                $linhasReceitas, ['data', 'texto', 'texto', 'texto', 'texto', 'texto', 'data', 'data', 'moeda'])
            ->aba('Despesas', ['Data', 'Descrição', 'Categoria', 'Status', 'Vencimento', 'Valor'],
                $linhasDespesas, ['data', 'texto', 'texto', 'texto', 'data', 'moeda'])
            ->aba('Recorrentes', ['Tipo', 'Descrição', 'Categoria', 'Frequência', 'Início', 'Fim', 'Valor de cada', 'Vezes no período', 'Total no período'],
                $linhasRec, ['texto', 'texto', 'texto', 'texto', 'data', 'data', 'moeda', 'numero', 'moeda'])
            ->aba('Por categoria', ['Categoria', 'Faturamento', 'Recebido', 'A receber', '% do total'],
                $linhasCategoria, ['texto', 'moeda', 'moeda', 'moeda', 'percentual']);

        return $xlsx->download('financeiro_' . $dataInicio . '_a_' . $dataFim . '.xlsx');
    }

    public function detalhes(Request $request)
    {
        $tipo = $request->get('tipo'); // 'receitas' ou 'despesas'
        $status = $request->get('status'); // 'RECEBIDA' ou 'PENDENTE'
        $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->get('data_fim', now()->endOfMonth()->format('Y-m-d'));

        if ($tipo === 'receitas') {
            $query = Receita::noPeriodo($dataInicio, $dataFim);
        } else { // despesas
            $query = Despesas::noPeriodo($dataInicio, $dataFim);
        }

        if ($status) {
            if ($tipo === 'receitas') {
                $query->where('status', $status);
            } else { // despesas são gravadas como PENDING / PAID
                $query->whereIn('status', $status === 'PENDENTE' ? ['PENDING', 'PENDENTE'] : ['PAID', 'PAGA', 'PAGO']);
            }
        }

        $dados = $query->get(['descricao', 'valor', 'status', 'data_vencimento']);

        return response()->json($dados);
    }
}
