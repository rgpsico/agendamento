@extends('barbearia.layout')
@section('area', 'dono')
@section('titulo', 'Visão geral')
@section('head')
    <style>
        .abas-dono { display:flex; gap:8px; flex-wrap:wrap; margin:0 0 14px; }
        .abas-dono a { padding:8px 14px; border-radius:999px; border:1px solid var(--linha); background:var(--cartao); color:inherit; text-decoration:none; font-weight:600; font-size:.92rem; }
        .abas-dono a.ativa { background:var(--fundo); color:#fff; border-color:var(--fundo); }
        .numeros { display:grid; grid-template-columns:repeat(auto-fit,minmax(130px,1fr)); gap:10px; margin:14px 0; }
        .numero { background:var(--cartao); border:1px solid var(--linha); border-radius:14px; padding:14px; }
        .numero b { display:block; font-size:2rem; line-height:1.1; color:var(--fundo); font-variant-numeric:tabular-nums; }
        .numero span { font-size:.85rem; color:var(--suave); }
        .barras { display:flex; align-items:flex-end; gap:8px; height:120px; margin-top:10px; }
        .barras .dia { flex:1; display:flex; flex-direction:column; justify-content:flex-end; align-items:center; height:100%; font-size:.78rem; color:var(--suave); }
        .barras .dia i { display:block; width:100%; background:#4fa3c7; border-radius:6px 6px 0 0; min-height:2px; }
        .barras .dia b { color:var(--tinta); font-weight:700; margin-bottom:3px; }
    </style>
@endsection
@section('conteudo')
    @include('barbearia._dono_menu', ['aba' => 'visao'])
    <p class="suave">Atualiza sozinha a cada 10 segundos.</p>

    <div id="caixa-visao" data-url="{{ route('barbearia.visao', ['empresa' => $empresa->id, 'fragmento' => 1]) }}">
        @include('barbearia._visao')
    </div>

    <script>
        (function () {
            var caixa = document.getElementById('caixa-visao');
            setInterval(function () {
                if (document.hidden) return;
                fetch(caixa.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                    .then(function (r) { return r.ok ? r.text() : null; })
                    .then(function (html) { if (html !== null) caixa.innerHTML = html; })
                    .catch(function () {});
            }, 10000);
        })();
    </script>
@endsection
