@extends('barbearia.layout')
@section('titulo', optional($barbeiro->usuario)->nome)
@section('conteudo')
    <p class="passos">Passo 2 de 2 · entre na fila</p>
    <p class="suave"><a href="{{ route('barbearia.show', $empresa) }}">← {{ $empresa->nome }}</a></p>
    <div class="cartao" style="margin-bottom:14px">
        <span class="avatar">{{ mb_strtoupper(mb_substr(optional($barbeiro->usuario)->nome ?? '?', 0, 1)) }}</span>
        <span class="meio"><strong>{{ optional($barbeiro->usuario)->nome }}</strong><span class="suave">{{ $nicho->termo('profissional') }}</span></span>
        <span class="selo {{ $esperando ? '' : 'vazio' }}">{{ $esperando ? $esperando.' na fila' : 'sem fila' }}</span>
    </div>

    @auth
        @if($minhaFila)
            <div class="aviso info">Você já está na fila desta barbearia.</div>
            <a class="btn" href="{{ route('barbearia.fila', $minhaFila) }}">Ver minha posição</a>
        @elseif(auth()->user()->tipo_usuario !== 'Aluno')
            <div class="aviso info">Você está logado como {{ mb_strtolower($nicho->termo('profissional')) }}/dono. Para entrar na fila, use a conta de cliente.</div>
        @elseif(! $noLocal)
            <div class="aviso info">Para entrar na fila, escaneie o QR code que está na barbearia.</div>
        @else
            <form method="post" action="{{ route('barbearia.checkin', [$empresa, $barbeiro]) }}">
                @csrf
                <button class="btn" type="submit">Fazer check-in e entrar na fila</button>
            </form>
            <p class="suave" style="margin-top:10px">Você será chamado na ordem de chegada.</p>
        @endif
    @else
        <p class="suave">Entre ou faça o cadastro rápido para fazer o check-in.</p>
        @include('barbearia._acesso', ['voltar' => '/barbearia/'.$empresa->id.'/barbeiro/'.$barbeiro->id])
    @endauth
@endsection
