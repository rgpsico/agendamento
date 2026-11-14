@php($atendendo = $fila->firstWhere('status', 'atendendo'))
@php($esperando = $fila->where('status', 'aguardando')->values())
{{-- ids de quem espera: a tela compara a cada atualização para tocar o alarme quando chega gente nova --}}
<span id="ids-espera" data-ids="{{ $esperando->pluck('id')->implode(',') }}" hidden></span>

<div class="caixa" style="display:flex;justify-content:space-around;text-align:center;gap:8px">
    <div><div class="grande" style="font-size:2rem">{{ $esperando->count() }}</div><div class="suave">esperando</div></div>
    <div><div class="grande" style="font-size:2rem">{{ $atendendo ? 1 : 0 }}</div><div class="suave">em atendimento</div></div>
    <div><div class="grande" style="font-size:2rem">{{ $atendidosHoje }}</div><div class="suave">atendidos hoje</div></div>
</div>

@if($atendendo)
    <h2>Em atendimento</h2>
    <div class="cartao" style="border-color:var(--ouro)">
        <span class="avatar">✂️</span>
        <span class="meio"><strong>{{ optional(optional($clientes->get($atendendo->aluno_id))->usuario)->nome ?? 'Cliente' }}</strong>
            <span class="suave">desde {{ $atendendo->iniciado_em ? $atendendo->iniciado_em->format('H:i') : '—' }}</span></span>
        <form method="post" action="{{ route('barbearia.painel.fila.finalizar', $atendendo) }}" style="margin:0">@csrf<button class="btn pequeno" type="submit">Finalizar</button></form>
    </div>
@endif

<h2>Fila de espera</h2>
<div class="lista">
    @forelse($esperando as $i => $f)
        @php($c = $clientes->get($f->aluno_id))
        {{-- WhatsApp do cliente: o barbeiro toca em Chamar/Enviar e o WhatsApp DELE abre com a mensagem pronta, só falta enviar --}}
        @php($zap = \App\Support\Telefone::whatsapp(optional(optional($c)->usuario)->telefone))
        @php($apresentacao = 'Oi '.\Illuminate\Support\Str::of(optional(optional($c)->usuario)->nome ?? '')->before(' ').'! Aqui é '.optional($barbeiro->usuario)->nome.' da '.$barbeiro->empresa->nome.'. ')
        <div class="cartao" style="flex-wrap:wrap">
            <span class="avatar">{{ $i + 1 }}º</span>
            <span class="meio">
                <strong>{{ optional(optional($c)->usuario)->nome ?? 'Cliente' }}</strong>
                <span class="suave">chegou às {{ $f->chegou_em->format('H:i') }}
                    @if($f->chamado_em) · <strong style="color:var(--ok)">chamado às {{ $f->chamado_em->format('H:i') }}</strong>@endif
                    @if($horarioHoje->has($f->aluno_id)) · tem horário marcado às {{ $horarioHoje->get($f->aluno_id) }}@endif
                </span>
                @if($f->mensagem)
                    <span class="suave" style="display:block;margin-top:4px">Você perguntou às {{ $f->mensagem_em->format('H:i') }}:
                        @if($f->resposta)<strong style="color:var(--ok)">{{ $f->resposta }}</strong> (às {{ $f->resposta_em->format('H:i') }})@else sem resposta ainda @endif
                    </span>
                @endif
            </span>
            <details class="recado" style="width:100%;margin-top:8px">
                <summary class="btn claro pequeno" style="width:100%;list-style:none">{{ $f->mensagem ? 'Perguntar de novo' : 'Perguntar onde está' }}</summary>
                <form method="post" action="{{ route('barbearia.painel.fila.mensagem', $f) }}" style="margin:8px 0 0"
                      @if($zap) data-wa-base="https://wa.me/{{ $zap }}" data-wa-prefixo="{{ $apresentacao }}" @endif>
                    @csrf
                    <input type="text" name="texto" maxlength="140" value="Onde você está? Já vai ser a sua vez." aria-label="Mensagem para o cliente" style="width:100%;margin-bottom:8px">
                    <button class="btn pequeno" style="width:100%" type="submit">{{ $zap ? 'Enviar pelo WhatsApp' : 'Enviar mensagem' }}</button>
                </form>
            </details>
            <div class="linha-acoes" style="width:100%;margin-top:8px">
                @unless($f->chamado_em)
                    <form method="post" action="{{ route('barbearia.painel.fila.chamar', $f) }}" style="margin:0;flex:1"
                          @if($zap) data-wa="https://wa.me/{{ $zap }}?text={{ rawurlencode($apresentacao.'É a sua vez, pode vir! 💈') }}" @endif>@csrf<button class="btn claro pequeno" style="width:100%" type="submit">Chamar</button></form>
                @endunless
                @unless($atendendo)
                    <form method="post" action="{{ route('barbearia.painel.fila.iniciar', $f) }}" style="margin:0;flex:1">@csrf<button class="btn pequeno" style="width:100%" type="submit">Iniciar</button></form>
                @endunless
                <form method="post" action="{{ route('barbearia.painel.fila.ausente', $f) }}" style="margin:0;flex:1">@csrf<button class="btn claro pequeno" style="width:100%" type="submit">Não apareceu</button></form>
            </div>
        </div>
    @empty
        <div class="aviso info">Ninguém esperando agora. Quando um cliente fizer check-in, ele aparece aqui.</div>
    @endforelse
</div>

<h2>Agenda de hoje</h2>
<div class="caixa">
    <table class="tabela">
        @forelse($agenda as $a)
            @php($c = $clientes->get($a->aluno_id))
            <tr>
                <td style="width:64px"><strong>{{ substr((string) $a->horario, 0, 5) ?: '—' }}</strong></td>
                <td>{{ optional(optional($c)->usuario)->nome ?? 'Cliente' }}<br><span class="suave">{{ optional($a->servico)->titulo }}</span></td>
                <td style="text-align:right">@if(in_array($a->aluno_id, $naFilaIds))<span class="selo vazio">chegou</span>@endif</td>
            </tr>
        @empty
            <tr><td class="suave">Nenhum horário marcado para hoje.</td></tr>
        @endforelse
    </table>
</div>
