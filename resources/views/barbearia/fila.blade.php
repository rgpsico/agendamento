@extends('barbearia.layout')
@section('titulo', 'Minha posição')
@section('conteudo')
    <h1>{{ $empresa->nome }}</h1>
    <p class="suave">{{ $nicho->termo('profissional') }}: {{ optional($fila->professor->usuario)->nome }}</p>

    <div id="caixa-fila" data-url="{{ route('barbearia.fila', ['fila' => $fila, 'fragmento' => 1]) }}">
        @include('barbearia._status_fila')
    </div>

    @if($fila->aberta())
        <script>
            // atualiza a posição sozinha a cada 8 segundos; vibra o celular quando o barbeiro chama
            (function () {
                var caixa = document.getElementById('caixa-fila');
                var chamado = caixa.querySelector('#status-fila').dataset.chamado === '1';
                var mensagem = caixa.querySelector('#status-fila').dataset.mensagem;
                var timer = setInterval(function () {
                    if (document.hidden) return;
                    fetch(caixa.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                        .then(function (r) { return r.ok ? r.text() : null; })
                        .then(function (html) {
                            if (html === null) return;
                            caixa.innerHTML = html;
                            var s = caixa.querySelector('#status-fila');
                            if (s && s.dataset.chamado === '1' && !chamado && navigator.vibrate) navigator.vibrate([300, 150, 300]);
                            // recado novo do barbeiro: vibra (a mensagem aparece no topo)
                            if (s && s.dataset.mensagem !== '0' && s.dataset.mensagem !== mensagem && navigator.vibrate) navigator.vibrate([300, 150, 300, 150, 300]);
                            mensagem = s ? s.dataset.mensagem : mensagem;
                            chamado = !!s && s.dataset.chamado === '1';
                            if (s && s.dataset.aberta !== '1') clearInterval(timer);
                        })
                        .catch(function () {});
                }, 8000);
            })();
        </script>
    @endif
@endsection
