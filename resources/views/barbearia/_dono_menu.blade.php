{{-- Abas do painel do dono. Espera $empresa, $barbearias, $souBarbeiro e $aba ('visao' ou 'equipe'). --}}
<h1>{{ $empresa->nome }}</h1>
<nav class="abas-dono">
    <a href="{{ route('barbearia.visao', ['empresa' => $empresa->id]) }}" class="{{ $aba === 'visao' ? 'ativa' : '' }}">Visão geral</a>
    <a href="{{ route('barbearia.equipe', ['empresa' => $empresa->id]) }}" class="{{ $aba === 'equipe' ? 'ativa' : '' }}">Equipe, QR e capa</a>
    @if($souBarbeiro)<a href="{{ route('barbearia.painel.fila') }}">Minha fila</a>@endif
</nav>
@if($barbearias->count() > 1)
    <form method="get" style="margin-bottom:12px">
        <select name="empresa" onchange="this.form.submit()" style="width:100%;padding:12px;border-radius:10px;border:1px solid var(--linha)">
            @foreach($barbearias as $b)<option value="{{ $b->id }}" @selected($b->id === $empresa->id)>{{ $b->nome }}</option>@endforeach
        </select>
    </form>
@endif
