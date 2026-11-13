@extends('barbearia.layout')
@section('titulo', $empresa->nome)
@section('conteudo')
    @include('barbearia._capa', ['empresa' => $empresa, 'classe' => 'capa-pagina'])
    <p class="passos">Passo 1 de 2 · escolha o {{ mb_strtolower($nicho->termo('profissional')) }}</p>
    <h1>{{ $empresa->nome }}</h1>
    <p class="suave">{{ $empresa->descricao }}</p>

    @unless($noLocal)
        <div class="aviso info">Para entrar na fila, escaneie o QR code que está na barbearia. Você já pode ver os {{ mb_strtolower($nicho->termo('profissionais')) }}.</div>
    @endunless

    <h2>Escolha o {{ mb_strtolower($nicho->termo('profissional')) }}</h2>
    <div class="lista">
        @forelse($barbeiros as $b)
            <a class="cartao" href="{{ route('barbearia.barbeiro', [$empresa, $b]) }}">
                <span class="avatar">{{ mb_strtoupper(mb_substr(optional($b->usuario)->nome ?? '?', 0, 1)) }}</span>
                <span class="meio"><strong>{{ optional($b->usuario)->nome }}</strong></span>
                <span class="selo {{ $b->esperando ? '' : 'vazio' }}">{{ $b->esperando ? $b->esperando.' na fila' : 'sem fila' }}</span>
            </a>
        @empty
            <div class="aviso info">Esta barbearia ainda não cadastrou {{ mb_strtolower($nicho->termo('profissionais')) }}.</div>
        @endforelse
    </div>
@endsection
