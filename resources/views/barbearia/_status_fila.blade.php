<div id="status-fila" data-aberta="{{ $fila->aberta() ? 1 : 0 }}" data-chamado="{{ $fila->chamado_em && $fila->status === 'aguardando' ? 1 : 0 }}">
    @if($fila->status === 'aguardando' && $fila->chamado_em)
        <div class="aviso ok" style="font-size:1.1rem;text-align:center"><strong>É a sua vez!</strong><br>Vá até {{ optional($fila->professor->usuario)->nome }}.</div>
    @endif

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
        @elseif($fila->status === 'ausente')
            <p><strong>Você foi retirado da fila.</strong><br><span class="suave">O barbeiro chamou e você não apareceu. Faça um novo check-in se ainda quiser ser atendido.</span></p>
        @else
            <p><strong>Você saiu da fila.</strong></p>
        @endif
    </div>

    @if($fila->aberta())
        <p class="suave">Chegada às {{ $fila->chegou_em->format('H:i') }}. Esta página atualiza sozinha.</p>
        <form method="post" action="{{ route('barbearia.fila.desistir', $fila) }}" style="margin-top:10px">@csrf<button class="btn claro" type="submit">Sair da fila</button></form>
    @else
        <a class="btn" href="{{ route('barbearia.show', $empresa) }}">Voltar para a barbearia</a>
    @endif
</div>
