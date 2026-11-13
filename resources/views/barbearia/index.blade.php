@extends('barbearia.layout')
@section('titulo', 'Barbearias')
@section('conteudo')
    <h1>Escolha a barbearia</h1>
    <p class="suave">Veja os {{ $nicho ? mb_strtolower($nicho->termo('profissionais')) : 'barbeiros' }} da casa e entre na fila.</p>

    <div class="lista">
        @forelse($barbearias as $b)
            <a class="barbearia" href="{{ route('barbearia.show', $b) }}">
                @include('barbearia._capa', ['empresa' => $b, 'classe' => 'capa-lista'])
                <span class="corpo">
                    <span class="meio"><strong>{{ $b->nome }}</strong><span class="suave">{{ $b->descricao }}</span></span>
                    <span class="selo {{ $b->esperando ? '' : 'vazio' }}">{{ $b->esperando ? $b->esperando.' na fila' : 'sem fila' }}</span>
                </span>
            </a>
        @empty
            <div class="aviso info">Nenhuma barbearia cadastrada ainda.</div>
        @endforelse
    </div>
    <p class="suave" style="text-align:center;margin-top:22px">É {{ $nicho ? mb_strtolower($nicho->termo('profissional')) : 'barbeiro' }} ou dono? <a href="{{ route('barbearia.login') }}"><strong>Entre no seu painel</strong></a></p>
@endsection
