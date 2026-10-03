@php
    // Logo do cabeçalho do painel (professor e aluno).
    // Nicho que não é o original: logo da empresa > logo do nicho > emoji + nome do nicho.
    // Nicho original (pilates) ou sem nicho: comportamento de sempre.
    $user = Auth::user();
    $empresa = $user->empresa ?? null;
    $avatar = $empresa->avatar ?? '';

    $nicho = app()->has('currentNicho') ? app('currentNicho') : null;
    $marcaPropria = $nicho && ! $nicho->usaMarcaGlobal();

    $temAvatar = $avatar && is_file(public_path('avatar/' . $avatar));
    $logoNicho = $marcaPropria ? $nicho->logo_url : null;

    $adminLogoLarge = null;
    $adminLogoSmall = asset('admin/img/logo-small.png');

    if (! $marcaPropria) {
        $configuracaoGeral = \App\Models\ConfiguracaoGeral::first();
        if (($configuracaoGeral->sistema_tipo ?? null) === 'pilates') {
            foreach (['png', 'jpg'] as $ext) {
                if (file_exists(public_path("admin/img/pilates_logo.$ext"))) {
                    $adminLogoLarge = $adminLogoSmall = asset("admin/img/pilates_logo.$ext");
                    break;
                }
            }
        }
    }
@endphp
<div class="header-left">
    <a href="{{ route('home.index') }}" class="logo">
        @if ($marcaPropria)
            @if ($temAvatar)
                <x-logo-tipo imagem="{{ $avatar }}" largura="154" altura="80" />
            @elseif ($logoNicho)
                <img src="{{ $logoNicho }}" alt="Logo" width="154" height="80" style="object-fit: contain;">
            @else
                <span class="fw-bold" style="font-size:1.15rem;color:#0f172a;line-height:80px;white-space:nowrap;">{{ $nicho->marcaTexto() }}</span>
            @endif
        @elseif ($adminLogoLarge)
            <img src="{{ $adminLogoLarge }}" alt="Logo" width="154" height="80" style="object-fit: contain;">
        @else
            <x-logo-tipo imagem="{{ $avatar }}" largura="154" altura="80" />
        @endif
    </a>
    <a href="{{ route('home.index') }}" class="logo logo-small">
        @if ($marcaPropria)
            @if ($logoNicho)
                <img src="{{ $logoNicho }}" alt="Logo" width="30" height="30" style="object-fit: contain;">
            @else
                <span style="font-size:24px;line-height:30px;">{{ $nicho->emoji ?: mb_substr($nicho->nome, 0, 1) }}</span>
            @endif
        @else
            <img src="{{ $adminLogoSmall }}" alt="Logo" width="30" height="30">
        @endif
    </a>
</div>
