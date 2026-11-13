@extends('barbearia.layout')
@section('titulo', 'Minha fila')
@section('conteudo')
    <h1>Olá, {{ auth()->user()->nome }}</h1>
    <p class="suave">{{ $barbeiro->empresa->nome }} · fila e agenda de hoje</p>

    <div id="painel-fila" data-url="{{ route('barbearia.painel.fila', ['fragmento' => 1]) }}">
        @include('barbearia._fila_barbeiro')
    </div>

    <script>
        // atualiza a fila sozinha a cada 8 segundos (sem recarregar a página)
        (function () {
            var caixa = document.getElementById('painel-fila');
            setInterval(function () {
                if (document.hidden) return;
                fetch(caixa.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                    .then(function (r) { return r.ok ? r.text() : null; })
                    .then(function (html) { if (html !== null) caixa.innerHTML = html; })
                    .catch(function () {});
            }, 8000);
        })();
    </script>
@endsection
