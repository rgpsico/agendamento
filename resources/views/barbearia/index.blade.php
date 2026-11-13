@extends('barbearia.layout')
@section('titulo', 'Barbearias')
@section('conteudo')
    <h1>Escolha a barbearia</h1>
    <p class="suave">Veja os {{ $nicho ? mb_strtolower($nicho->termo('profissionais')) : 'barbeiros' }} da casa e entre na fila.</p>

    <div class="lista">
        @forelse($barbearias as $b)
            <a class="cartao" href="{{ route('barbearia.show', $b) }}">
                <span class="avatar">{{ mb_strtoupper(mb_substr($b->nome, 0, 1)) }}</span>
                <span class="meio"><strong>{{ $b->nome }}</strong><span class="suave">{{ $b->descricao }}</span></span>
            </a>
        @empty
            <div class="aviso info">Nenhuma barbearia cadastrada ainda.</div>
        @endforelse
    </div>
@endsection
