@extends('barbearia.layout')
@section('titulo', 'Minha posição')
@section('conteudo')
    <h1>{{ $empresa->nome }}</h1>
    <p class="suave">{{ $nicho->termo('profissional') }}: {{ optional($fila->professor->usuario)->nome }}</p>

    <div class="caixa" style="text-align:center">
        @if($fila->status === 'aguardando')
            <div class="suave">Sua posição na fila</div>
            <div class="grande">{{ $posicao }}º</div>
            <p class="suave" style="margin-top:10px">{{ $posicao === 1 ? 'Você é o próximo! Fique por perto.' : ($posicao - 1).' pessoa'.($posicao - 1 > 1 ? 's' : '').' na sua frente.' }}</p>
        @elseif($fila->status === 'atendendo')
            <div class="grande">✂️</div>
            <p><strong>Você está sendo atendido.</strong></p>
        @elseif($fila->status === 'concluido')
            <div class="grande">✔</div>
            <p><strong>Atendimento concluído.</strong> Obrigado pela visita!</p>
        @else
            <p><strong>Você saiu da fila.</strong></p>
        @endif
    </div>

    @if($fila->aberta())
        <p class="suave">Chegada às {{ $fila->chegou_em->format('H:i') }}. Esta página não atualiza sozinha: recarregue para ver sua posição.</p>
        <a class="btn escuro" href="{{ route('barbearia.fila', $fila) }}">Atualizar posição</a>
        <form method="post" action="{{ route('barbearia.fila.desistir', $fila) }}" style="margin-top:10px">@csrf<button class="btn claro" type="submit">Sair da fila</button></form>
    @else
        <a class="btn" href="{{ route('barbearia.show', $empresa) }}">Voltar para a barbearia</a>
    @endif
@endsection
