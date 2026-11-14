<div class="numeros">
    <div class="numero"><b>{{ $esperando->count() }}</b><span>na fila agora</span></div>
    <div class="numero"><b>{{ $emAtendimento }}</b><span>em atendimento</span></div>
    <div class="numero"><b>{{ $atendidosHoje }}</b><span>atendidos hoje</span></div>
    <div class="numero"><b>{{ $esperaMedia === null ? '—' : $esperaMedia.' min' }}</b><span>espera média hoje</span></div>
    <div class="numero"><b>{{ $duracaoMedia === null ? '—' : $duracaoMedia.' min' }}</b><span>duração média do corte</span></div>
    <div class="numero"><b>{{ $ausentesHoje + $desistiramHoje }}</b><span>não atendidos hoje<br>{{ $ausentesHoje }} não apareceram · {{ $desistiramHoje }} desistiram</span></div>
</div>

<h2>Barbeiros agora</h2>
<div class="lista">
    @forelse($porBarbeiro as $p)
        <div class="cartao">
            <span class="avatar">{{ mb_strtoupper(mb_substr(optional($p->barbeiro->usuario)->nome ?? '?', 0, 1)) }}</span>
            <span class="meio">
                <strong>{{ optional($p->barbeiro->usuario)->nome }}</strong>
                <span class="suave">
                    @if($p->atendendo)
                        ✂ atendendo {{ $p->cliente_atendendo ?? 'cliente' }} desde {{ $p->atendendo->iniciado_em ? $p->atendendo->iniciado_em->format('H:i') : '—' }}
                    @else
                        livre
                    @endif
                    · {{ $p->atendidos_hoje }} atendido{{ $p->atendidos_hoje === 1 ? '' : 's' }} hoje
                </span>
            </span>
            <span class="selo {{ $p->esperando ? '' : 'vazio' }}">{{ $p->esperando ? $p->esperando.' na fila' : 'sem fila' }}</span>
        </div>
    @empty
        <div class="aviso info">Nenhum barbeiro ativo. Cadastre em "Equipe, QR e capa".</div>
    @endforelse
</div>

<h2>Todos que estão esperando</h2>
<div class="caixa">
    <table class="tabela">
        @forelse($esperando as $i => $f)
            <tr>
                <td style="width:44px"><strong>{{ $i + 1 }}º</strong></td>
                <td>{{ optional(optional($clientes->get($f->aluno_id))->usuario)->nome ?? 'Cliente' }}<br><span class="suave">com {{ $nomes->get($f->professor_id) ?? 'barbeiro' }}</span></td>
                <td style="text-align:right" class="suave">chegou {{ $f->chegou_em->format('H:i') }}<br>há {{ $f->chegou_em->diffInMinutes(now()) }} min</td>
            </tr>
        @empty
            <tr><td class="suave">Ninguém esperando agora.</td></tr>
        @endforelse
    </table>
</div>

<h2>Últimos 7 dias</h2>
<div class="caixa">
    <strong>{{ $totalSemana }}</strong> <span class="suave">atendimento{{ $totalSemana === 1 ? '' : 's' }} concluído{{ $totalSemana === 1 ? '' : 's' }} na semana</span>
    <div class="barras">
        @foreach($dias as $d)
            <div class="dia">
                <b>{{ $d->total }}</b>
                <i style="height:{{ max(2, round($d->total / $maxDia * 80)) }}px"></i>
                <span style="margin-top:4px">{{ $loop->last ? 'hoje' : $d->data->locale('pt_BR')->isoFormat('ddd') }}</span>
            </div>
        @endforeach
    </div>
</div>
