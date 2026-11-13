@php
    // cada papel tem a sua cara: cliente (claro), barbeiro (escuro, modo trabalho) e dono (cabeçalho azul)
    $area = trim($__env->yieldContent('area')) ?: 'cliente';
    $rotulo = ['barbeiro' => '✂ Painel do barbeiro', 'dono' => '🏪 Painel do dono'][$area] ?? null;
    $cor1 = $nicho->cor_primaria ?? '#1f1f1f';
    $cor2 = $nicho->cor_secundaria ?? '#c9a24b';
    $marca = $nicho ? $nicho->marcaTexto() : '💈 Barbearia';
@endphp
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    @yield('head')
    <title>@yield('titulo', 'Barbearia') · {{ $nicho->nome ?? 'Barbearia' }}</title>
    <style>
        :root { --fundo:{{ $cor1 }}; --ouro:{{ $cor2 }}; --papel:#f6f3ec; --cartao:#fff; --tinta:#1b1b1b; --suave:#6b665c; --linha:#e3ddd0; --ok:#2f7d4f; --erro:#b3261e; }
        * { box-sizing:border-box; }
        body { margin:0; font:16px/1.45 system-ui,-apple-system,'Segoe UI',sans-serif; background:var(--papel); color:var(--tinta); }
        a { color:inherit; }
        header.topo { background:var(--fundo); color:#fff; padding:14px 16px; display:flex; align-items:center; justify-content:space-between; gap:12px; }
        header.topo a { color:#fff; text-decoration:none; font-weight:700; letter-spacing:.3px; }
        header.topo form { margin:0; }
        header.topo button { background:none; border:1px solid rgba(255,255,255,.4); color:#fff; border-radius:999px; padding:6px 12px; font-size:13px; cursor:pointer; }
        main { max-width:640px; margin:0 auto; padding:20px 16px 48px; }
        h1 { font-size:1.45rem; margin:0 0 4px; text-wrap:balance; }
        h2 { font-size:1.1rem; margin:24px 0 10px; }
        p.suave, .suave { color:var(--suave); margin:0 0 14px; }
        .lista { display:grid; gap:10px; margin:14px 0; }
        a.cartao, .cartao { display:flex; align-items:center; gap:14px; background:var(--cartao); border:1px solid var(--linha); border-radius:14px; padding:14px; color:inherit; text-decoration:none; }
        a.cartao:hover { border-color:var(--ouro); }
        .avatar { width:52px; height:52px; border-radius:50%; background:var(--fundo); color:var(--ouro); display:grid; place-items:center; font-weight:700; font-size:1.2rem; flex:none; }
        .cartao .meio { flex:1; min-width:0; }
        .cartao strong { display:block; }
        .selo { font-size:12px; padding:3px 9px; border-radius:999px; background:#efe7d1; color:#5b4a14; white-space:nowrap; }
        .selo.vazio { background:#e3f1e8; color:var(--ok); }
        .btn { display:inline-block; width:100%; border:0; border-radius:12px; padding:14px 16px; font-size:1rem; font-weight:700; background:var(--ouro); color:#1b1b1b; cursor:pointer; text-align:center; text-decoration:none; }
        .btn.escuro { background:var(--fundo); color:#fff; }
        .btn.claro { background:var(--cartao); border:1px solid var(--linha); color:var(--tinta); }
        .btn.pequeno { width:auto; padding:8px 14px; font-size:.9rem; }
        .aviso { border-radius:12px; padding:12px 14px; margin:0 0 14px; font-size:.95rem; }
        .aviso.ok { background:#e3f1e8; color:#1d4d31; }
        .aviso.erro { background:#fbe7e5; color:#7a1812; }
        .aviso.info { background:#efe7d1; color:#5b4a14; }
        label { display:block; font-weight:600; font-size:.9rem; margin:12px 0 4px; }
        input[type=text], input[type=tel], input[type=password] { color:var(--tinta); width:100%; padding:13px 14px; border:1px solid var(--linha); border-radius:10px; font-size:1rem; background:var(--cartao); }
        input:focus { outline:2px solid var(--ouro); border-color:var(--ouro); }
        .campo-erro { color:var(--erro); font-size:.85rem; margin-top:4px; }
        .caixa { background:var(--cartao); border:1px solid var(--linha); border-radius:14px; padding:16px; margin:14px 0; }
        .abas { display:flex; gap:8px; margin-bottom:6px; }
        .abas button { flex:1; padding:10px; border-radius:10px; border:1px solid var(--linha); background:var(--cartao); font-weight:700; cursor:pointer; }
        .abas button.ativa { background:var(--fundo); color:#fff; border-color:var(--fundo); }
        .grande { font-size:3.4rem; font-weight:800; line-height:1; color:var(--fundo); }
        .tabela { width:100%; border-collapse:collapse; }
        .tabela td { padding:10px 0; border-bottom:1px solid var(--linha); vertical-align:middle; }
        .linha-acoes { display:flex; gap:8px; align-items:center; justify-content:space-between; }
        .capa { display:block; width:100%; aspect-ratio:16/7; object-fit:cover; background:var(--fundo); }
        .capa.vazia { background:repeating-linear-gradient(135deg, var(--fundo) 0 16px, #2c2a25 16px 32px); border-bottom:3px solid var(--ouro); }
        .capa-pagina { border-radius:14px; margin:0 0 16px; }
        .capa-pagina.vazia { border-bottom:0; }
        a.barbearia { display:block; background:var(--cartao); border:1px solid var(--linha); border-radius:14px; overflow:hidden; color:inherit; text-decoration:none; }
        a.barbearia:hover { border-color:var(--ouro); }
        a.barbearia .corpo { display:flex; align-items:center; gap:12px; padding:12px 14px; }
        a.barbearia .corpo .meio { flex:1; min-width:0; }
        a.barbearia .corpo strong { display:block; }
        .chip { font-size:12px; font-weight:800; letter-spacing:.8px; text-transform:uppercase; padding:5px 11px; border-radius:999px; background:var(--ouro); color:#1b1b1b; white-space:nowrap; }
        .passos { font-size:12px; font-weight:700; letter-spacing:.6px; text-transform:uppercase; color:var(--suave); margin:0 0 6px; }

        /* barbeiro: modo trabalho, tudo escuro e com números grandes */
        body.area-barbeiro { --papel:#121212; --cartao:#1e1e1e; --tinta:#f1ede2; --suave:#a49f92; --linha:#34312b; --fundo:#000; }
        .area-barbeiro header.topo { border-bottom:3px solid var(--ouro); }
        .area-barbeiro .grande { color:var(--ouro); }
        .area-barbeiro .avatar { background:#2d2a22; }
        .area-barbeiro .btn.escuro { background:var(--ouro); color:#1b1b1b; }
        .area-barbeiro .btn.claro { background:#2a2a2a; color:var(--tinta); border-color:#3b3b3b; }
        .area-barbeiro .selo { background:#3a3319; color:#e7d9a0; }
        .area-barbeiro .selo.vazio { background:#1f3a2a; color:#8fd4a9; }
        .area-barbeiro .aviso.info { background:#2e2914; color:#e7d9a0; }
        .area-barbeiro .aviso.ok { background:#17301f; color:#9be0b5; }
        .area-barbeiro .aviso.erro { background:#3a1b18; color:#f4a8a1; }
        .area-barbeiro .tabela td { border-bottom-color:#34312b; }

        /* dono: cabeçalho azul-petróleo para não confundir com o barbeiro nem com o cliente */
        body.area-dono { --fundo:#1d3140; }
        .area-dono header.topo { border-bottom:3px solid #4fa3c7; }
        .area-dono .chip { background:#4fa3c7; color:#fff; }
    </style>
</head>
<body class="area-{{ $area }}">
<header class="topo">
    <a href="{{ $area === 'cliente' ? route('barbearia.index') : route('barbearia.painel') }}">{{ $marca }}</a>
    @if($rotulo)<span class="chip">{{ $rotulo }}</span>@endif
    @auth
        <form method="post" action="{{ route('barbearia.sair') }}">@csrf<button type="submit">Sair</button></form>
    @else
        <a href="{{ route('barbearia.login') }}" style="font-weight:400;font-size:14px">Entrar</a>
    @endauth
</header>
<main>
    @if(session('ok'))<div class="aviso ok">{{ session('ok') }}</div>@endif
    @if(session('erro'))<div class="aviso erro">{{ session('erro') }}</div>@endif
    @yield('conteudo')
</main>
</body>
</html>
