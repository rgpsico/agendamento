@php
    $zap = preg_replace('/\D/', '', $site->whatsapp ?? '');
    $zapBase = $zap ? 'https://wa.me/' . $zap : null;
    $capaUrl = $site->capa ? asset('storage/' . $site->capa) : null;
    $logoUrl = $site->logo ? asset('storage/' . $site->logo) : null;
    $sobreUrl = $site->sobre_imagem ? asset('storage/' . $site->sobre_imagem) : null;
    $galeria = collect(\Illuminate\Support\Facades\Storage::disk('public')->files('sites/galeria/' . $site->id))->sort()->values()
        ->map(fn ($f) => asset('storage/' . $f));
    $duracao = function ($min) {
        if (!$min) return null;
        $h = intdiv((int) $min, 60); $m = (int) $min % 60;
        return $h ? $h . 'h' . ($m ? sprintf('%02d', $m) : '') : $m . ' min';
    };
    // Passeios do site (site_servicos); se não houver, usa os serviços agendáveis da empresa.
    $svRows = $site->siteServicos ?? collect();
    if ($svRows->count()) {
        $passeios = $svRows->sortBy('id')->values()->map(fn ($x) => (object) [
            'id' => $x->id, 'titulo' => $x->titulo, 'descricao' => $x->descricao,
            'img' => $x->imagem ? (str_starts_with($x->imagem, 'sites/') ? asset('storage/' . $x->imagem) : asset('servico/' . $x->imagem)) : null,
            'credito' => $x->imagem_credito, 'credito_url' => $x->imagem_credito_url,
            'principal' => (bool) $x->destaque, 'tr' => $x->traducoes ?? [],
            'preco' => (float) $x->preco, 'duracao_txt' => $x->duracao ?: null,
        ]);
    } else {
        $passeios = ($site->servicos ?? collect())->map(fn ($x) => (object) [
            'id' => 's' . $x->id, 'titulo' => $x->titulo, 'descricao' => $x->descricao,
            'img' => ($x->imagem && !str_contains($x->imagem, 'passeio_padrao')) ? asset('servico/' . $x->imagem) : null,
            'credito' => null, 'credito_url' => null, 'principal' => true, 'tr' => [],
            'preco' => (float) $x->preco, 'duracao_txt' => $duracao($x->tempo_de_aula),
        ]);
    }
    $principais = $passeios->where('principal', true)->values();
    $outros = $passeios->where('principal', false)->values();
    $svMap = $passeios->mapWithKeys(fn ($x) => [(string) $x->id => array_merge(['pt' => ['t' => $x->titulo, 'd' => $x->descricao]], $x->tr)]);
    $depoimentos = $site->depoimentos ?? collect();
    $temSobre = filled($site->sobre_titulo) || filled($site->sobre_descricao) || !empty($site->sobre_itens);
    $contatos = collect($site->contatos ?? [])->keyBy(fn ($c) => strtolower($c->tipo));
    $instagram = $contatos->get('instagram')?->valor;
    $emailContato = $contatos->get('email')?->valor;
    $descSeo = $site->descricao ?: 'Passeios e experiências no Rio de Janeiro.';
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $site->titulo }}</title>
    <meta name="description" content="{{ $descSeo }}">
    @unless($site->ativo)<meta name="robots" content="noindex, nofollow">@endunless
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $site->titulo }}">
    <meta property="og:description" content="{{ $descSeo }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($capaUrl)<meta property="og:image" content="{{ $capaUrl }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <script type="application/ld+json">
    {!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'TravelAgency',
        'name' => $site->titulo,
        'description' => $descSeo,
        'url' => url()->current(),
        'image' => $capaUrl,
        'telephone' => $zap ? '+' . $zap : null,
        'areaServed' => 'Rio de Janeiro',
    ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script>document.documentElement.classList.add('js');</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:#0b2545; --ocean:#0b5ea8; --turq:#14b8c4; --turq-dark:#0e8f99; --gold:#f2b705;
            --ink:#0f2238; --muted:#53657a; --bg:#f5f9fc; --card:#ffffff; --line:#dde7f0; --zap:#1fa855;
            --radius:18px; --shadow:0 10px 30px rgba(11,37,69,.10);
            --head:"Plus Jakarta Sans","Inter",system-ui,sans-serif; --body:"Inter",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
        }
        * { box-sizing:border-box; }
        html { scroll-behavior:smooth; scroll-padding-top:76px; }
        body { margin:0; font-family:var(--body); color:var(--ink); background:var(--bg); line-height:1.6; padding-bottom:84px; -webkit-font-smoothing:antialiased; }
        h1,h2,h3 { font-family:var(--head); letter-spacing:-.02em; margin:0; text-wrap:balance; }
        a { color:inherit; }
        img { max-width:100%; display:block; }
        .wrap { width:min(1160px,100% - 32px); margin-inline:auto; }
        .skip { position:absolute; left:-999px; top:8px; background:#fff; color:var(--navy); padding:8px 14px; border-radius:8px; z-index:100; }
        .skip:focus { left:8px; }
        :focus-visible { outline:3px solid var(--gold); outline-offset:3px; }

        /* Botões */
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:10px; min-height:52px; padding:14px 26px; border-radius:999px; font:700 1rem var(--head); text-decoration:none; border:0; cursor:pointer; transition:transform .2s, box-shadow .2s, background .2s; }
        .btn:hover { transform:translateY(-2px); }
        .btn--zap { background:var(--zap); color:#fff; box-shadow:0 8px 22px rgba(31,168,85,.35); }
        .btn--gold { background:var(--gold); color:#2a2000; }
        .btn--ghost { background:rgba(255,255,255,.14); color:#fff; border:1.5px solid rgba(255,255,255,.7); backdrop-filter:blur(4px); }
        .btn--ocean { background:var(--ocean); color:#fff; }
        .btn svg { width:20px; height:20px; fill:currentColor; flex:none; }

        /* Header */
        header.top { position:fixed; inset:0 0 auto 0; z-index:50; transition:background .25s, box-shadow .25s; color:#fff; }
        header.top.solid { background:rgba(11,37,69,.96); backdrop-filter:blur(10px); box-shadow:0 4px 20px rgba(0,0,0,.18); }
        header.top .wrap { display:flex; align-items:center; gap:16px; height:68px; }
        .brand { display:flex; align-items:center; gap:10px; font:800 1.1rem var(--head); text-decoration:none; margin-right:auto; }
        .brand img { height:38px; width:auto; }
        .brand .mark { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,var(--turq),var(--ocean)); display:grid; place-items:center; }
        .brand .mark svg { width:20px; height:20px; fill:#fff; }
        nav.menu { display:flex; gap:26px; }
        nav.menu a { text-decoration:none; font-weight:500; font-size:.95rem; opacity:.92; }
        nav.menu a:hover { opacity:1; color:var(--gold); }
        .lang { position:relative; }
        .lang > button { display:flex; align-items:center; gap:8px; background:rgba(255,255,255,.12); color:#fff; border:1px solid rgba(255,255,255,.35); border-radius:999px; padding:8px 12px; cursor:pointer; font:600 .85rem var(--body); min-height:40px; }
        .lang ul { position:absolute; right:0; top:calc(100% + 8px); margin:0; padding:6px; list-style:none; background:#fff; color:var(--ink); border-radius:14px; box-shadow:var(--shadow); min-width:170px; display:none; }
        .lang.open ul { display:block; }
        .lang li button { width:100%; display:flex; align-items:center; gap:10px; padding:10px 12px; border:0; background:none; border-radius:10px; cursor:pointer; font:500 .95rem var(--body); color:inherit; text-align:left; }
        .lang li button:hover, .lang li button[aria-current="true"] { background:#e8f3fb; }
        .flag { width:24px; height:16px; border-radius:3px; flex:none; box-shadow:0 0 0 1px rgba(0,0,0,.15); }
        .burger { display:none; width:44px; height:44px; background:none; border:0; color:#fff; cursor:pointer; }
        .burger svg { width:28px; height:28px; stroke:#fff; }
        @media (max-width:860px) {
            .burger { display:block; }
            nav.menu { position:fixed; inset:68px 0 auto 0; background:var(--navy); flex-direction:column; gap:0; padding:8px 16px 20px; display:none; box-shadow:0 20px 30px rgba(0,0,0,.25); }
            nav.menu.open { display:flex; }
            nav.menu a { padding:16px 4px; border-bottom:1px solid rgba(255,255,255,.1); font-size:1.05rem; }
        }

        /* Hero */
        .hero { position:relative; min-height:100svh; display:flex; align-items:center; color:#fff; background:var(--navy) center/cover no-repeat; }
        .hero::before { content:""; position:absolute; inset:0; background:linear-gradient(180deg,rgba(11,37,69,.55) 0%,rgba(11,37,69,.35) 40%,rgba(11,37,69,.85) 100%); }
        .hero .wrap { position:relative; padding-block:120px 72px; }
        .hero h1 { font-size:clamp(2.3rem,7.2vw,4.6rem); line-height:1.04; max-width:15ch; font-weight:800; }
        .hero p.sub { font-size:clamp(1.05rem,2.6vw,1.3rem); max-width:52ch; margin:20px 0 32px; opacity:.95; }
        .cta-row { display:flex; gap:14px; flex-wrap:wrap; }
        .trust { display:flex; gap:10px 26px; flex-wrap:wrap; margin:36px 0 0; padding:0; list-style:none; font-weight:500; font-size:.95rem; }
        .trust li::before { content:"✓"; color:var(--gold); font-weight:800; margin-right:8px; }

        section { padding-block:clamp(56px,9vw,104px); }
        .eyebrow { color:var(--turq-dark); font:700 .8rem var(--head); letter-spacing:.14em; text-transform:uppercase; margin-bottom:10px; }
        h2.title { font-size:clamp(1.8rem,4.6vw,2.8rem); line-height:1.1; margin-bottom:12px; }
        .lead { color:var(--muted); max-width:56ch; margin:0 0 36px; font-size:1.05rem; }

        /* Passeios */
        .tours { display:grid; grid-template-columns:repeat(auto-fill,minmax(290px,1fr)); gap:24px; }
        .tour { background:var(--card); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow); display:flex; flex-direction:column; transition:transform .25s, box-shadow .25s; }
        .tour:hover { transform:translateY(-6px); box-shadow:0 18px 40px rgba(11,37,69,.16); }
        .tour .ph { aspect-ratio:16/10; background:linear-gradient(135deg,var(--ocean),var(--turq)); display:grid; place-items:center; overflow:hidden; }
        .tour .ph img { width:100%; height:100%; object-fit:cover; }
        .tour .ph svg { width:64px; height:64px; fill:rgba(255,255,255,.9); }
        .tour .bd { padding:22px; display:flex; flex-direction:column; gap:12px; flex:1; }
        .tour h3 { font-size:1.3rem; }
        .tour p { margin:0; color:var(--muted); }
        .chips { display:flex; gap:8px; flex-wrap:wrap; }
        .chip { background:#e6f4f6; color:var(--turq-dark); font:600 .85rem var(--body); padding:5px 12px; border-radius:999px; }
        .price { margin-top:auto; font-size:.85rem; color:var(--muted); }
        .price strong { display:block; font:800 1.5rem var(--head); color:var(--navy); }
        .tour .ph { position:relative; }
        .credit { position:absolute; right:8px; bottom:8px; font:500 .7rem var(--body); background:rgba(0,0,0,.55); color:#fff; padding:3px 9px; border-radius:99px; text-decoration:none; backdrop-filter:blur(4px); }
        .credit:hover { background:rgba(0,0,0,.8); }
        .tour:not(.small) .ph { aspect-ratio:4/3; }
        .tour .ph img { object-position:center 62%; }
        .tour.small .ph { aspect-ratio:16/9; } .tour.small .ph img { object-position:center; }
        .tour .ph img[src*="festa-favela"] { object-position:center 92%; }
        @media (min-width:760px) { .tours.main { grid-template-columns:repeat(2,1fr); } }
        .tour .actions { display:flex; gap:10px; flex-wrap:wrap; }
        .tour .actions .btn { flex:1; min-height:48px; padding:12px 18px; }

        /* Galeria */
        .masonry { display:flex; flex-wrap:wrap; justify-content:center; gap:12px; }
        .masonry button { flex:0 0 calc((100% - 12px)/2); padding:0; border:0; background:none; cursor:zoom-in; border-radius:14px; overflow:hidden; position:relative; aspect-ratio:4/5; }
        .masonry button:nth-child(3n+1) { aspect-ratio:1/1; }
        .masonry img { width:100%; height:100%; object-fit:cover; object-position:center 30%; transition:transform .5s; }
        .masonry button:hover img { transform:scale(1.05); }
        @media (min-width:820px) { .masonry { gap:16px; } .masonry button { flex-basis:calc((100% - 32px)/3); aspect-ratio:4/5; } .masonry button:nth-child(3n+1) { aspect-ratio:4/5; } }
        .lb { position:fixed; inset:0; background:rgba(5,15,30,.94); z-index:200; display:none; align-items:center; justify-content:center; padding:20px; }
        .lb.open { display:flex; }
        .lb img { max-height:86vh; max-width:min(92vw,1000px); border-radius:12px; touch-action:pan-y; }
        .lb button { position:absolute; background:rgba(255,255,255,.14); color:#fff; border:0; width:48px; height:48px; border-radius:50%; font-size:1.6rem; cursor:pointer; display:grid; place-items:center; }
        .lb .x { top:16px; right:16px; } .lb .pv { left:14px; top:50%; } .lb .nx { right:14px; top:50%; }
        .lb .ct { position:absolute; bottom:18px; left:50%; transform:translateX(-50%); color:#fff; font-size:.9rem; opacity:.8; }

        /* Por que */
        .why { background:#fff; }
        .why .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:18px; }
        .why .item { padding:24px; border-radius:var(--radius); background:var(--bg); border:1px solid var(--line); }
        .why .ic { font-size:1.9rem; line-height:1; margin-bottom:12px; }
        .why h3 { font-size:1.08rem; margin-bottom:6px; }
        .why p { margin:0; color:var(--muted); font-size:.95rem; }

        /* Como funciona */
        .steps { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:22px; counter-reset:s; }
        .step { position:relative; padding:28px 24px 24px; background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); }
        .step::before { counter-increment:s; content:counter(s); position:absolute; top:-18px; left:22px; width:42px; height:42px; border-radius:50%; background:var(--gold); color:#2a2000; font:800 1.15rem var(--head); display:grid; place-items:center; }
        .step h3 { margin:6px 0 8px; font-size:1.15rem; }
        .step p { margin:0; color:var(--muted); }

        /* Depoimentos (só reais) */
        .reviews { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:20px; }
        .review { background:var(--card); border-radius:var(--radius); padding:24px; box-shadow:var(--shadow); }
        .stars { color:var(--gold); letter-spacing:2px; }
        .review footer { display:flex; align-items:center; gap:12px; margin-top:16px; font-weight:600; }
        .review footer img { width:44px; height:44px; border-radius:50%; object-fit:cover; }

        /* Sobre */
        .about .grid { display:grid; gap:36px; align-items:center; }
        .about img.cover { width:100%; border-radius:var(--radius); box-shadow:var(--shadow); }
        .about p { color:var(--muted); font-size:1.05rem; }
        @media (min-width:860px) { .about .grid { grid-template-columns:1fr 1fr; } }

        /* Contato */
        .contact { background:linear-gradient(180deg,#eaf4fb,var(--bg)); }
        .contact .box { display:grid; gap:32px; }
        @media (min-width:900px) { .contact .box { grid-template-columns:5fr 7fr; align-items:start; } }
        .quick { display:flex; flex-direction:column; gap:14px; align-items:flex-start; }
        form.card { background:#fff; border-radius:var(--radius); padding:clamp(20px,4vw,34px); box-shadow:var(--shadow); display:grid; gap:14px; grid-template-columns:1fr 1fr; }
        form.card .full { grid-column:1/-1; }
        @media (max-width:560px) { form.card { grid-template-columns:1fr; } }
        label { display:grid; gap:6px; font:600 .88rem var(--body); color:var(--navy); }
        input, select, textarea { font:400 1rem var(--body); padding:13px 14px; border:1.5px solid var(--line); border-radius:12px; background:#fff; color:var(--ink); width:100%; min-height:48px; }
        input:focus, select:focus, textarea:focus { border-color:var(--turq); outline:none; box-shadow:0 0 0 3px rgba(20,184,196,.2); }
        textarea { min-height:104px; resize:vertical; }
        .hp { position:absolute; left:-9999px; }
        .msg { padding:14px 16px; border-radius:12px; font-weight:500; display:none; }
        .msg.ok { display:block; background:#e5f7ec; color:#14532d; }
        .msg.err { display:block; background:#fdecec; color:#7f1d1d; }

        /* CTA final */
        .final { position:relative; color:#fff; text-align:center; background:var(--navy) center/cover no-repeat; }
        .final::before { content:""; position:absolute; inset:0; background:rgba(11,37,69,.72); }
        .final .wrap { position:relative; }
        .final h2 { font-size:clamp(2rem,5.5vw,3.4rem); margin-bottom:14px; }
        .final p { max-width:52ch; margin:0 auto 30px; opacity:.95; font-size:1.1rem; }
        .final .cta-row { justify-content:center; }

        /* Footer */
        footer.site { background:var(--navy); color:#cfdcec; padding-block:48px 28px; }
        footer.site .cols { display:grid; gap:28px; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); }
        footer.site h4 { color:#fff; font:700 .9rem var(--head); text-transform:uppercase; letter-spacing:.1em; margin:0 0 12px; }
        footer.site ul { list-style:none; margin:0; padding:0; display:grid; gap:8px; }
        footer.site a { text-decoration:none; } footer.site a:hover { color:var(--gold); }
        footer.site .copy { margin-top:32px; padding-top:20px; border-top:1px solid rgba(255,255,255,.12); font-size:.85rem; display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center; }
        footer.site .lang ul { bottom:calc(100% + 8px); top:auto; }

        /* WhatsApp flutuante */
        .fab { position:fixed; right:16px; bottom:calc(16px + env(safe-area-inset-bottom,0px)); z-index:90; display:flex; align-items:center; gap:10px; flex-direction:row-reverse; text-decoration:none; }
        .fab .bt { position:relative; width:58px; height:58px; border-radius:50%; background:var(--zap); display:grid; place-items:center; box-shadow:0 10px 26px rgba(0,0,0,.28); }
        .fab .bt svg { width:30px; height:30px; fill:#fff; }
        .fab .bt::after { content:""; position:absolute; inset:0; border-radius:50%; border:2px solid var(--zap); animation:pulse 2.6s ease-out infinite; }
        .fab .tip { background:#fff; color:var(--navy); font:600 .9rem var(--body); padding:9px 14px; border-radius:999px; box-shadow:var(--shadow); opacity:0; transform:translateX(8px); transition:.2s; pointer-events:none; white-space:nowrap; }
        .fab:hover .tip, .fab:focus-visible .tip { opacity:1; transform:none; }
        @keyframes pulse { 0% { transform:scale(1); opacity:.7; } 70%,100% { transform:scale(1.55); opacity:0; } }
        @media (max-width:560px) { .fab .bt { width:54px; height:54px; } }

        /* Entrada suave no scroll (visível se JS/IO indisponível) */
        .js .reveal { opacity:0; transform:translateY(22px); transition:opacity .7s ease, transform .7s ease; }
        .js .reveal.in { opacity:1; transform:none; }
        @media (prefers-reduced-motion:reduce) {
            html { scroll-behavior:auto; }
            .js .reveal { opacity:1; transform:none; transition:none; }
            .fab .bt::after { animation:none; } .btn, .tour, .masonry img { transition:none; }
        }
    </style>
</head>
<body data-zap="{{ $zap }}" data-site="{{ $site->titulo }}" data-contato-url="{{ url('/site/' . $site->slug . '/contato') }}">
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <defs>
        <symbol id="f-br" viewBox="0 0 24 16"><rect width="24" height="16" fill="#009b3a"/><path d="M12 2.2 21 8l-9 5.8L3 8z" fill="#fedf00"/><circle cx="12" cy="8" r="3.3" fill="#002776"/></symbol>
        <symbol id="f-us" viewBox="0 0 24 16"><rect width="24" height="16" fill="#fff"/><g fill="#b22234"><rect width="24" height="1.23"/><rect y="2.46" width="24" height="1.23"/><rect y="4.92" width="24" height="1.23"/><rect y="7.38" width="24" height="1.23"/><rect y="9.85" width="24" height="1.23"/><rect y="12.31" width="24" height="1.23"/><rect y="14.77" width="24" height="1.23"/></g><rect width="9.6" height="8.6" fill="#3c3b6e"/></symbol>
        <symbol id="f-es" viewBox="0 0 24 16"><rect width="24" height="16" fill="#c60b1e"/><rect y="4" width="24" height="8" fill="#ffc400"/></symbol>
        <symbol id="f-it" viewBox="0 0 24 16"><rect width="8" height="16" fill="#009246"/><rect x="8" width="8" height="16" fill="#fff"/><rect x="16" width="8" height="16" fill="#ce2b37"/></symbol>
        <symbol id="i-zap" viewBox="0 0 32 32"><path d="M16 3C9 3 3.4 8.6 3.4 15.5c0 2.4.7 4.7 1.9 6.6L3 29l7.1-2.2a12.6 12.6 0 0 0 5.9 1.5c7 0 12.6-5.6 12.6-12.5S23 3 16 3zm0 22.8c-1.9 0-3.7-.5-5.3-1.5l-.4-.2-4.200 1.300 1.400-4-.3-.4a10.200 10.200 0 0 1-1.600-5.500C5.600 9.900 10.300 5.300 16 5.300s10.400 4.600 10.400 10.200S21.700 25.800 16 25.800zm5.700-7.600c-.3-.2-1.800-.9-2.100-1-.3-.1-.5-.2-.7.2s-.8 1-1 1.200c-.2.200-.4.200-.7.100a8.400 8.400 0 0 1-4.200-3.700c-.3-.6.300-.5.900-1.700.1-.2 0-.4 0-.5l-1-2.300c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.100-.8.400s-1 1-1 2.500 1.100 2.900 1.200 3.100c.2.200 2.100 3.300 5.200 4.500 2.500 1 3 .8 3.500.7.600-.1 1.800-.7 2-1.400.3-.7.3-1.300.2-1.400-.1-.1-.3-.2-.6-.4z"/></symbol>
        <symbol id="i-compass" viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm4.200 5.800-2.100 6.300-6.300 2.100 2.100-6.300z"/></symbol>
    </defs>
</svg>

<a class="skip" href="#main" data-i18n="skip">Ir para o conteúdo</a>

<header class="top" id="top">
    <div class="wrap">
        <a class="brand" href="#inicio" aria-label="{{ $site->titulo }}">
            @if($logoUrl)<img src="{{ $logoUrl }}" alt="{{ $site->titulo }}">
            @else<span class="mark"><svg><use href="#i-compass"/></svg></span><span>{{ $site->titulo }}</span>@endif
        </a>
        <nav class="menu" id="menu" aria-label="Menu">
            <a href="#inicio" data-i18n="nav_home">Início</a>
            @if($passeios->count())<a href="#passeios" data-i18n="nav_tours">Passeios</a>@endif
            @if($galeria->count())<a href="#galeria" data-i18n="nav_gallery">Galeria</a>@endif
            @if($temSobre)<a href="#sobre" data-i18n="nav_about">Sobre nós</a>@endif
            <a href="#contato" data-i18n="nav_contact">Contato</a>
        </nav>
        <div class="lang" data-lang>
            <button type="button" aria-haspopup="true" aria-expanded="false" aria-label="Idioma / Language">
                <svg class="flag"><use href="#f-br"/></svg><span class="cur">PT</span>
            </button>
            <ul role="menu">
                <li><button type="button" data-l="pt"><svg class="flag"><use href="#f-br"/></svg>Português</button></li>
                <li><button type="button" data-l="en"><svg class="flag"><use href="#f-us"/></svg>English</button></li>
                <li><button type="button" data-l="es"><svg class="flag"><use href="#f-es"/></svg>Español</button></li>
                <li><button type="button" data-l="it"><svg class="flag"><use href="#f-it"/></svg>Italiano</button></li>
            </ul>
        </div>
        <button class="burger" id="burger" type="button" aria-label="Menu" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    </div>
</header>

<main id="main">
<section class="hero" id="inicio" @if($capaUrl) style="background-image:url('{{ $capaUrl }}')" @endif>
    <div class="wrap">
        <h1 data-i18n="hero_title">Viva experiências que você nunca vai esquecer.</h1>
        <p class="sub" data-i18n="hero_sub">Descubra passeios, praias e experiências incríveis e encontre a aventura perfeita para sua viagem.</p>
        <div class="cta-row">
            <a class="btn btn--gold" href="#{{ $passeios->count() ? 'passeios' : 'contato' }}" data-i18n="btn_explore">Explorar passeios</a>
            @if($zapBase)<a class="btn btn--ghost" href="{{ $zapBase }}?text={{ rawurlencode('Olá! Vi o site da ' . $site->titulo . ' e gostaria de saber mais.') }}" target="_blank" rel="noopener" data-wa="hello"><svg><use href="#i-zap"/></svg><span data-i18n="btn_whats">Falar pelo WhatsApp</span></a>@endif
        </div>
        <ul class="trust">
            <li data-i18n="trust1">Atendimento rápido</li>
            <li data-i18n="trust2">Reserva fácil</li>
            <li data-i18n="trust3">Suporte durante sua experiência</li>
        </ul>
    </div>
</section>

@if($passeios->count())
<section id="passeios">
    <div class="wrap">
        <div class="reveal">
            <div class="eyebrow" data-i18n="tours_eyebrow">Passeios</div>
            <h2 class="title" data-i18n="tours_title">Experiências que você vai amar</h2>
        </div>
        <div class="tours main">
            @foreach($principais as $p) @include('site._passeio_card', ['p' => $p]) @endforeach
        </div>
        @if($outros->count())
            <div class="reveal" style="margin-top:64px">
                <div class="eyebrow" data-i18n="tours_other_eyebrow">Também organizamos</div>
                <h2 class="title" style="font-size:clamp(1.4rem,3.4vw,2rem)" data-i18n="tours_other_title">Outros passeios no Rio</h2>
            </div>
            <div class="tours">
                @foreach($outros as $p) @include('site._passeio_card', ['p' => $p]) @endforeach
            </div>
        @endif
    </div>
</section>
@endif

@if($galeria->count())
<section id="galeria" style="padding-top:0">
    <div class="wrap">
        <div class="reveal">
            <div class="eyebrow" data-i18n="gal_eyebrow">Galeria</div>
            <h2 class="title" data-i18n="gal_title">Momentos inesquecíveis</h2>
        </div>
        <div class="masonry reveal" id="masonry">
            @foreach($galeria as $i => $foto)
                <button type="button" data-i="{{ $i }}" aria-label="Foto {{ $i + 1 }}"><img src="{{ $foto }}" alt="Foto de um passeio da {{ $site->titulo }}" loading="lazy"></button>
            @endforeach
        </div>
    </div>
</section>
<div class="lb" id="lb" role="dialog" aria-modal="true" aria-label="Galeria">
    <button class="x" type="button" aria-label="Fechar">×</button>
    <button class="pv" type="button" aria-label="Anterior">‹</button>
    <img id="lbimg" src="" alt="">
    <button class="nx" type="button" aria-label="Próxima">›</button>
    <div class="ct" id="lbct"></div>
</div>
@endif

<section class="why">
    <div class="wrap">
        <div class="reveal">
            <div class="eyebrow" data-i18n="why_eyebrow">Por que escolher a gente</div>
            <h2 class="title" data-i18n="why_title">Viaje com tranquilidade</h2>
        </div>
        <div class="grid">
            @foreach ([['🌎','why1'],['🛡️','why2'],['💬','why3'],['⭐','why4'],['🗺️','why5']] as [$ic,$k])
                <div class="item reveal"><div class="ic" aria-hidden="true">{{ $ic }}</div><h3 data-i18n="{{ $k }}_t"></h3><p data-i18n="{{ $k }}_d"></p></div>
            @endforeach
        </div>
    </div>
</section>

<section>
    <div class="wrap">
        <div class="reveal">
            <div class="eyebrow" data-i18n="how_eyebrow">Como funciona</div>
            <h2 class="title" data-i18n="how_title">Reservar é simples</h2>
        </div>
        <div class="steps" style="margin-top:34px">
            @foreach ([1,2,3] as $n)
                <div class="step reveal"><h3 data-i18n="step{{ $n }}_t"></h3><p data-i18n="step{{ $n }}_d"></p></div>
            @endforeach
        </div>
    </div>
</section>

@if($depoimentos->count())
<section style="padding-top:0">
    <div class="wrap">
        <div class="reveal"><h2 class="title" data-i18n="rev_title">O que dizem nossos clientes</h2></div>
        <div class="reviews" style="margin-top:28px">
            @foreach($depoimentos as $d)
                <figure class="review reveal" style="margin:0">
                    @if($d->nota)<div class="stars" aria-label="{{ $d->nota }} / 5">{{ str_repeat('★', (int) round($d->nota)) }}{{ str_repeat('☆', 5 - (int) round($d->nota)) }}</div>@endif
                    <blockquote style="margin:10px 0 0">“{{ $d->comentario }}”</blockquote>
                    <footer>@if($d->foto)<img src="{{ asset('storage/' . $d->foto) }}" alt="" loading="lazy">@endif<span>{{ $d->nome }}</span></footer>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($temSobre)
<section id="sobre" class="about">
    <div class="wrap grid">
        <div class="reveal">
            <div class="eyebrow" data-i18n="nav_about">Sobre nós</div>
            @if($site->sobre_titulo)<h2 class="title">{{ $site->sobre_titulo }}</h2>@endif
            @if($site->sobre_descricao)<p>{{ $site->sobre_descricao }}</p>@endif
        </div>
        @if($sobreUrl)<img class="cover reveal" src="{{ $sobreUrl }}" alt="{{ $site->titulo }}" loading="lazy">@endif
    </div>
</section>
@endif

<section id="contato" class="contact">
    <div class="wrap box">
        <div class="reveal">
            <div class="eyebrow" data-i18n="nav_contact">Contato</div>
            <h2 class="title" data-i18n="form_title">Vamos planejar sua próxima experiência?</h2>
            <p class="lead" data-i18n="form_sub">Conte para nós o que você procura e nossa equipe entrará em contato.</p>
            @if($zapBase)
            <div class="quick">
                <a class="btn btn--zap" href="{{ $zapBase }}?text={{ rawurlencode('Olá! Vi o site da ' . $site->titulo . ' e gostaria de saber mais.') }}" target="_blank" rel="noopener" data-wa="hello"><svg><use href="#i-zap"/></svg><span data-i18n="btn_whats">Falar pelo WhatsApp</span></a>
                <small style="color:var(--muted)" data-i18n="quick_hint">Prefere conversar agora? Responda rápido por aqui.</small>
            </div>
            @endif
        </div>
        <form class="card reveal" id="form" novalidate>
            <label><span data-i18n="f_name">Nome</span><input name="nome" required autocomplete="name" maxlength="120"></label>
            <label><span data-i18n="f_email">E-mail</span><input name="email" type="email" required autocomplete="email" maxlength="160"></label>
            <label><span data-i18n="f_whats">WhatsApp</span><input name="whatsapp" type="tel" autocomplete="tel" maxlength="30" placeholder="+55 21 ..."></label>
            <label><span data-i18n="f_country">País</span><input name="pais" autocomplete="country-name" maxlength="80"></label>
            <label><span data-i18n="f_date">Data da viagem</span><input name="data" type="date"></label>
            <label><span data-i18n="f_people">Quantidade de pessoas</span><input name="pessoas" type="number" min="1" max="500" inputmode="numeric"></label>
            <label class="full"><span data-i18n="f_tour">Passeio de interesse</span>
                <select name="passeio" id="f-passeio">
                    <option value="" data-i18n="f_tour_any">Ainda não sei</option>
                    @foreach($passeios as $p)<option value="{{ $p->titulo }}" data-sv="{{ $p->id }}" data-f="t">{{ $p->titulo }}</option>@endforeach
                </select>
            </label>
            <label class="full"><span data-i18n="f_msg">Mensagem</span><textarea name="mensagem" maxlength="2000"></textarea></label>
            <input class="hp" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
            <div class="full"><button class="btn btn--ocean" type="submit" style="width:100%" data-i18n="f_send">Quero saber mais</button></div>
            <div class="msg full" id="msg" role="status"></div>
        </form>
    </div>
</section>

<section class="final" @if($capaUrl) style="background-image:url('{{ $capaUrl }}');background-position:center 22%" @endif>
    <div class="wrap reveal">
        <h2 data-i18n="final_title">Sua próxima história começa aqui.</h2>
        <p data-i18n="final_sub">Descubra experiências incríveis e transforme sua viagem em uma lembrança inesquecível.</p>
        <div class="cta-row">
            <a class="btn btn--gold" href="#{{ $passeios->count() ? 'passeios' : 'contato' }}" data-i18n="btn_explore">Explorar passeios</a>
            @if($zapBase)<a class="btn btn--ghost" href="{{ $zapBase }}?text={{ rawurlencode('Olá! Vi o site da ' . $site->titulo . ' e gostaria de saber mais.') }}" target="_blank" rel="noopener" data-wa="hello"><svg><use href="#i-zap"/></svg><span data-i18n="btn_whats">Falar pelo WhatsApp</span></a>@endif
        </div>
    </div>
</section>
</main>

<footer class="site">
    <div class="wrap">
        <div class="cols">
            <div>
                <h4>{{ $site->titulo }}</h4>
                <p style="margin:0;font-size:.95rem" data-i18n="foot_blurb">Passeios e experiências no Rio de Janeiro.</p>
            </div>
            <div>
                <h4 data-i18n="foot_menu">Menu</h4>
                <ul>
                    @if($passeios->count())<li><a href="#passeios" data-i18n="nav_tours">Passeios</a></li>@endif
                    @if($galeria->count())<li><a href="#galeria" data-i18n="nav_gallery">Galeria</a></li>@endif
                    @if($temSobre)<li><a href="#sobre" data-i18n="nav_about">Sobre nós</a></li>@endif
                    <li><a href="#contato" data-i18n="nav_contact">Contato</a></li>
                </ul>
            </div>
            <div>
                <h4 data-i18n="nav_contact">Contato</h4>
                <ul>
                    @if($zapBase)<li><a href="{{ $zapBase }}" target="_blank" rel="noopener">WhatsApp {{ $site->whatsapp }}</a></li>@endif
                    @if($instagram)<li><a href="{{ str_starts_with($instagram, 'http') ? $instagram : 'https://instagram.com/' . ltrim($instagram, '@') }}" target="_blank" rel="noopener">Instagram</a></li>@endif
                    @if($emailContato)<li><a href="mailto:{{ $emailContato }}">{{ $emailContato }}</a></li>@endif
                </ul>
            </div>
        </div>
        <div class="copy">
            <span>© {{ date('Y') }} {{ $site->titulo }}</span>
            <div class="lang" data-lang>
                <button type="button" aria-haspopup="true" aria-expanded="false" aria-label="Idioma / Language"><svg class="flag"><use href="#f-br"/></svg><span class="cur">PT</span></button>
                <ul role="menu">
                    <li><button type="button" data-l="pt"><svg class="flag"><use href="#f-br"/></svg>Português</button></li>
                    <li><button type="button" data-l="en"><svg class="flag"><use href="#f-us"/></svg>English</button></li>
                    <li><button type="button" data-l="es"><svg class="flag"><use href="#f-es"/></svg>Español</button></li>
                    <li><button type="button" data-l="it"><svg class="flag"><use href="#f-it"/></svg>Italiano</button></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

@if($zapBase)
<a class="fab" href="{{ $zapBase }}?text={{ rawurlencode('Olá! Vi o site da ' . $site->titulo . ' e gostaria de saber mais.') }}" target="_blank" rel="noopener" aria-label="WhatsApp" data-wa="hello">
    <span class="bt"><svg aria-hidden="true"><use href="#i-zap"/></svg></span>
    <span class="tip" data-i18n="fab_tip">Fale conosco pelo WhatsApp</span>
</a>
@endif

<script>window.SV = {!! json_encode($svMap, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!};</script>
@verbatim
<script>
(function () {
  var T = {
    pt: { skip:'Ir para o conteúdo', tours_other_eyebrow:'Também organizamos', tours_other_title:'Outros passeios no Rio', photo:'Foto', nav_home:'Início', nav_tours:'Passeios', nav_gallery:'Galeria', nav_about:'Sobre nós', nav_contact:'Contato',
      hero_title:'Viva experiências que você nunca vai esquecer.', hero_sub:'Descubra passeios, praias e experiências incríveis e encontre a aventura perfeita para sua viagem.',
      btn_explore:'Explorar passeios', btn_whats:'Falar pelo WhatsApp', trust1:'Atendimento rápido', trust2:'Reserva fácil', trust3:'Suporte durante sua experiência',
      tours_eyebrow:'Passeios', tours_title:'Experiências que você vai amar', duration:'Duração', from:'a partir de', view_exp:'Ver experiência',
      gal_eyebrow:'Galeria', gal_title:'Momentos inesquecíveis', why_eyebrow:'Por que escolher a gente', why_title:'Viaje com tranquilidade',
      why1_t:'Experiências selecionadas', why1_d:'Passeios escolhidos com cuidado para você aproveitar o melhor do Rio.',
      why2_t:'Reserva segura', why2_d:'Você combina tudo direto com a gente, com clareza sobre data e valor.',
      why3_t:'Atendimento rápido', why3_d:'Tire suas dúvidas pelo WhatsApp, sem complicação.',
      why4_t:'Clientes satisfeitos', why4_d:'O que mais importa é você voltar feliz da experiência.',
      why5_t:'Guias e parceiros locais', why5_d:'Quem conhece o Rio de verdade acompanha você.',
      how_eyebrow:'Como funciona', how_title:'Reservar é simples',
      step1_t:'Escolha sua experiência', step1_d:'Explore os passeios e encontre o que combina com sua viagem.',
      step2_t:'Entre em contato', step2_d:'Fale rapidamente pelo WhatsApp ou envie sua solicitação.',
      step3_t:'Aproveite', step3_d:'Receba as informações da reserva e aproveite sua experiência.',
      rev_title:'O que dizem nossos clientes', form_title:'Vamos planejar sua próxima experiência?', form_sub:'Conte para nós o que você procura e nossa equipe entrará em contato.',
      quick_hint:'Prefere conversar agora? Responda rápido por aqui.',
      f_name:'Nome', f_email:'E-mail', f_whats:'WhatsApp', f_country:'País', f_date:'Data da viagem', f_people:'Quantidade de pessoas', f_tour:'Passeio de interesse', f_tour_any:'Ainda não sei', f_msg:'Mensagem', f_send:'Quero saber mais',
      ok:'Recebemos seu contato! Para agilizar, continue pelo WhatsApp:', ok_btn:'Continuar no WhatsApp', err:'Não foi possível enviar agora. Tente de novo ou fale pelo WhatsApp.', err_req:'Preencha nome e um e-mail válido.',
      final_title:'Sua próxima história começa aqui.', final_sub:'Descubra experiências incríveis e transforme sua viagem em uma lembrança inesquecível.',
      foot_blurb:'Passeios e experiências no Rio de Janeiro.', foot_menu:'Menu', fab_tip:'Fale conosco pelo WhatsApp', hello:'Olá! Vi o site da {s} e gostaria de saber mais.' },
    en: { skip:'Skip to content', tours_other_eyebrow:'We also arrange', tours_other_title:'More ways to see Rio', photo:'Photo', nav_home:'Home', nav_tours:'Tours', nav_gallery:'Gallery', nav_about:'About us', nav_contact:'Contact',
      hero_title:'Live experiences you will never forget.', hero_sub:'Discover tours, beaches and amazing experiences and find the perfect adventure for your trip.',
      btn_explore:'Explore tours', btn_whats:'Chat on WhatsApp', trust1:'Fast response', trust2:'Easy booking', trust3:'Support during your experience',
      tours_eyebrow:'Tours', tours_title:'Experiences you will love', duration:'Duration', from:'from', view_exp:'View experience',
      gal_eyebrow:'Gallery', gal_title:'Unforgettable moments', why_eyebrow:'Why choose us', why_title:'Travel with peace of mind',
      why1_t:'Selected experiences', why1_d:'Tours chosen with care so you can enjoy the best of Rio.',
      why2_t:'Safe booking', why2_d:'You arrange everything directly with us, with clear date and price.',
      why3_t:'Fast response', why3_d:'Ask your questions on WhatsApp, no hassle.',
      why4_t:'Happy customers', why4_d:'What matters most is that you come back happy.',
      why5_t:'Local guides and partners', why5_d:'People who truly know Rio go with you.',
      how_eyebrow:'How it works', how_title:'Booking is simple',
      step1_t:'Choose your experience', step1_d:'Browse the tours and find what fits your trip.',
      step2_t:'Get in touch', step2_d:'Message us quickly on WhatsApp or send your request.',
      step3_t:'Enjoy', step3_d:'Receive your booking details and enjoy the experience.',
      rev_title:'What our customers say', form_title:"Let's plan your next experience?", form_sub:'Tell us what you are looking for and our team will get back to you.',
      quick_hint:'Prefer to talk now? We answer quickly here.',
      f_name:'Name', f_email:'E-mail', f_whats:'WhatsApp', f_country:'Country', f_date:'Travel date', f_people:'Number of people', f_tour:'Tour of interest', f_tour_any:'Not sure yet', f_msg:'Message', f_send:'I want to know more',
      ok:'We received your message! To speed things up, continue on WhatsApp:', ok_btn:'Continue on WhatsApp', err:'We could not send it now. Try again or chat on WhatsApp.', err_req:'Please enter your name and a valid e-mail.',
      final_title:'Your next story starts here.', final_sub:'Discover amazing experiences and turn your trip into an unforgettable memory.',
      foot_blurb:'Tours and experiences in Rio de Janeiro.', foot_menu:'Menu', fab_tip:'Chat with us on WhatsApp', hello:'Hello! I saw the {s} website and would like to know more.' },
    es: { skip:'Ir al contenido', tours_other_eyebrow:'También organizamos', tours_other_title:'Más formas de conocer Río', photo:'Foto', nav_home:'Inicio', nav_tours:'Paseos', nav_gallery:'Galería', nav_about:'Sobre nosotros', nav_contact:'Contacto',
      hero_title:'Vive experiencias que nunca vas a olvidar.', hero_sub:'Descubre paseos, playas y experiencias increíbles y encuentra la aventura perfecta para tu viaje.',
      btn_explore:'Explorar paseos', btn_whats:'Hablar por WhatsApp', trust1:'Atención rápida', trust2:'Reserva fácil', trust3:'Soporte durante tu experiencia',
      tours_eyebrow:'Paseos', tours_title:'Experiencias que vas a amar', duration:'Duración', from:'desde', view_exp:'Ver experiencia',
      gal_eyebrow:'Galería', gal_title:'Momentos inolvidables', why_eyebrow:'Por qué elegirnos', why_title:'Viaja con tranquilidad',
      why1_t:'Experiencias seleccionadas', why1_d:'Paseos elegidos con cuidado para que disfrutes lo mejor de Río.',
      why2_t:'Reserva segura', why2_d:'Acuerdas todo directamente con nosotros, con claridad en fecha y precio.',
      why3_t:'Atención rápida', why3_d:'Resuelve tus dudas por WhatsApp, sin complicaciones.',
      why4_t:'Clientes satisfechos', why4_d:'Lo más importante es que vuelvas feliz de la experiencia.',
      why5_t:'Guías y socios locales', why5_d:'Quien conoce Río de verdad te acompaña.',
      how_eyebrow:'Cómo funciona', how_title:'Reservar es simple',
      step1_t:'Elige tu experiencia', step1_d:'Explora los paseos y encuentra el que va con tu viaje.',
      step2_t:'Ponte en contacto', step2_d:'Escríbenos rápido por WhatsApp o envía tu solicitud.',
      step3_t:'Disfruta', step3_d:'Recibe la información de la reserva y disfruta tu experiencia.',
      rev_title:'Lo que dicen nuestros clientes', form_title:'¿Planeamos tu próxima experiencia?', form_sub:'Cuéntanos qué buscas y nuestro equipo se pondrá en contacto.',
      quick_hint:'¿Prefieres hablar ahora? Respondemos rápido por aquí.',
      f_name:'Nombre', f_email:'Correo electrónico', f_whats:'WhatsApp', f_country:'País', f_date:'Fecha del viaje', f_people:'Cantidad de personas', f_tour:'Paseo de interés', f_tour_any:'Aún no sé', f_msg:'Mensaje', f_send:'Quiero saber más',
      ok:'¡Recibimos tu contacto! Para agilizar, continúa por WhatsApp:', ok_btn:'Continuar en WhatsApp', err:'No pudimos enviarlo ahora. Inténtalo de nuevo o habla por WhatsApp.', err_req:'Completa tu nombre y un correo válido.',
      final_title:'Tu próxima historia empieza aquí.', final_sub:'Descubre experiencias increíbles y convierte tu viaje en un recuerdo inolvidable.',
      foot_blurb:'Paseos y experiencias en Río de Janeiro.', foot_menu:'Menú', fab_tip:'Habla con nosotros por WhatsApp', hello:'¡Hola! Vi el sitio de {s} y me gustaría saber más.' },
    it: { skip:'Vai al contenuto', tours_other_eyebrow:'Organizziamo anche', tours_other_title:'Altri modi per scoprire Rio', photo:'Foto', nav_home:'Home', nav_tours:'Tour', nav_gallery:'Galleria', nav_about:'Chi siamo', nav_contact:'Contatti',
      hero_title:'Vivi esperienze che non dimenticherai mai.', hero_sub:'Scopri tour, spiagge ed esperienze incredibili e trova l’avventura perfetta per il tuo viaggio.',
      btn_explore:'Esplora i tour', btn_whats:'Scrivici su WhatsApp', trust1:'Risposta rapida', trust2:'Prenotazione facile', trust3:'Assistenza durante la tua esperienza',
      tours_eyebrow:'Tour', tours_title:'Esperienze che amerai', duration:'Durata', from:'da', view_exp:'Vedi l’esperienza',
      gal_eyebrow:'Galleria', gal_title:'Momenti indimenticabili', why_eyebrow:'Perché sceglierci', why_title:'Viaggia con serenità',
      why1_t:'Esperienze selezionate', why1_d:'Tour scelti con cura per vivere il meglio di Rio.',
      why2_t:'Prenotazione sicura', why2_d:'Organizzi tutto direttamente con noi, con date e prezzi chiari.',
      why3_t:'Risposta rapida', why3_d:'Chiedi quello che vuoi su WhatsApp, senza complicazioni.',
      why4_t:'Clienti soddisfatti', why4_d:'Quello che conta è che torni felice dall’esperienza.',
      why5_t:'Guide e partner locali', why5_d:'Chi conosce davvero Rio ti accompagna.',
      how_eyebrow:'Come funziona', how_title:'Prenotare è semplice',
      step1_t:'Scegli la tua esperienza', step1_d:'Esplora i tour e trova quello adatto al tuo viaggio.',
      step2_t:'Contattaci', step2_d:'Scrivici subito su WhatsApp o invia la tua richiesta.',
      step3_t:'Goditela', step3_d:'Ricevi i dettagli della prenotazione e goditi l’esperienza.',
      rev_title:'Cosa dicono i nostri clienti', form_title:'Pianifichiamo la tua prossima esperienza?', form_sub:'Raccontaci cosa cerchi e il nostro team ti risponderà.',
      quick_hint:'Preferisci parlare ora? Rispondiamo subito qui.',
      f_name:'Nome', f_email:'E-mail', f_whats:'WhatsApp', f_country:'Paese', f_date:'Data del viaggio', f_people:'Numero di persone', f_tour:'Tour di interesse', f_tour_any:'Non so ancora', f_msg:'Messaggio', f_send:'Voglio saperne di più',
      ok:'Abbiamo ricevuto il tuo contatto! Per fare prima, continua su WhatsApp:', ok_btn:'Continua su WhatsApp', err:'Non siamo riusciti a inviare ora. Riprova o scrivici su WhatsApp.', err_req:'Inserisci il tuo nome e un’e-mail valida.',
      final_title:'La tua prossima storia inizia qui.', final_sub:'Scopri esperienze incredibili e trasforma il tuo viaggio in un ricordo indimenticabile.',
      foot_blurb:'Tour ed esperienze a Rio de Janeiro.', foot_menu:'Menu', fab_tip:'Scrivici su WhatsApp', hello:'Ciao! Ho visto il sito di {s} e vorrei saperne di più.' }
  };
  var CODES = { pt:'PT', en:'EN', es:'ES', it:'IT' }, FLAGS = { pt:'#f-br', en:'#f-us', es:'#f-es', it:'#f-it' }, HTMLLANG = { pt:'pt-BR', en:'en', es:'es', it:'it' };
  var body = document.body, siteName = body.dataset.site, zap = body.dataset.zap, lang = 'pt';
  var $ = function (s, r) { return (r || document).querySelector(s); }, $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  function setLang(l) {
    if (!T[l]) l = 'pt'; lang = l;
    try { localStorage.setItem('site_lang', l); } catch (e) {}
    document.documentElement.lang = HTMLLANG[l];
    $$('[data-i18n]').forEach(function (el) { var v = T[l][el.dataset.i18n]; if (v) el.textContent = v; });
    $$('[data-lang]').forEach(function (box) {
      $('.cur', box).textContent = CODES[l];
      $('button > svg use', box).setAttribute('href', FLAGS[l]);
      $$('li button', box).forEach(function (b) { b.setAttribute('aria-current', b.dataset.l === l ? 'true' : 'false'); });
    });
    $$('[data-sv]').forEach(function (el) {
      var d = (window.SV || {})[el.dataset.sv]; if (!d) return;
      var v = d[l] && d[l][el.dataset.f] ? d[l][el.dataset.f] : (d.pt ? d.pt[el.dataset.f] : null);
      if (v) el.textContent = v;
    });
    var hello = T[l].hello.replace('{s}', siteName);
    $$('[data-wa="hello"]').forEach(function (a) { a.href = 'https://wa.me/' + zap + '?text=' + encodeURIComponent(hello); });
  }
  var saved = null; try { saved = localStorage.getItem('site_lang'); } catch (e) {}
  var nav = (navigator.language || 'pt').slice(0, 2).toLowerCase();
  setLang(saved && T[saved] ? saved : (T[nav] ? nav : 'pt'));

  $$('[data-lang]').forEach(function (box) {
    $('button', box).addEventListener('click', function (e) { e.stopPropagation(); var o = box.classList.toggle('open'); this.setAttribute('aria-expanded', o); });
    $$('li button', box).forEach(function (b) { b.addEventListener('click', function () { setLang(b.dataset.l); box.classList.remove('open'); }); });
  });
  document.addEventListener('click', function () { $$('[data-lang]').forEach(function (b) { b.classList.remove('open'); }); });

  // Header e menu mobile
  var header = $('#top'), menu = $('#menu'), burger = $('#burger');
  function onScroll() { header.classList.toggle('solid', window.scrollY > 40); }
  onScroll(); window.addEventListener('scroll', onScroll, { passive: true });
  burger.addEventListener('click', function () { var o = menu.classList.toggle('open'); burger.setAttribute('aria-expanded', o); header.classList.toggle('solid', o || window.scrollY > 40); });
  $$('a', menu).forEach(function (a) { a.addEventListener('click', function () { menu.classList.remove('open'); burger.setAttribute('aria-expanded', 'false'); }); });

  // Entrada suave no scroll
  var rev = $$('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) { es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }); }, { threshold: .12 });
    rev.forEach(function (el) { io.observe(el); });
  } else { rev.forEach(function (el) { el.classList.add('in'); }); }

  // "Ver experiência" pré-seleciona o passeio no formulário
  $$('[data-tour]').forEach(function (a) { a.addEventListener('click', function () { var s = $('#f-passeio'); if (s) s.value = a.dataset.tour; }); });

  // Lightbox
  var lb = $('#lb');
  if (lb) {
    var imgs = $$('#masonry img').map(function (i) { return i.src; }), cur = 0, img = $('#lbimg'), ct = $('#lbct'), lastFocus = null;
    function show(i) { cur = (i + imgs.length) % imgs.length; img.src = imgs[cur]; ct.textContent = (cur + 1) + ' / ' + imgs.length; }
    function open(i) { lastFocus = document.activeElement; show(i); lb.classList.add('open'); document.body.style.overflow = 'hidden'; $('.x', lb).focus(); }
    function close() { lb.classList.remove('open'); document.body.style.overflow = ''; if (lastFocus) lastFocus.focus(); }
    $$('#masonry button').forEach(function (b) { b.addEventListener('click', function () { open(+b.dataset.i); }); });
    $('.x', lb).addEventListener('click', close); $('.pv', lb).addEventListener('click', function () { show(cur - 1); }); $('.nx', lb).addEventListener('click', function () { show(cur + 1); });
    lb.addEventListener('click', function (e) { if (e.target === lb) close(); });
    document.addEventListener('keydown', function (e) { if (!lb.classList.contains('open')) return; if (e.key === 'Escape') close(); if (e.key === 'ArrowLeft') show(cur - 1); if (e.key === 'ArrowRight') show(cur + 1); });
    var x0 = null; lb.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) { if (x0 === null) return; var dx = e.changedTouches[0].clientX - x0; if (Math.abs(dx) > 45) show(cur + (dx < 0 ? 1 : -1)); x0 = null; });
  }

  // Formulário
  var form = $('#form'), msg = $('#msg');
  form.addEventListener('submit', function (e) {
    e.preventDefault(); msg.className = 'msg'; msg.textContent = '';
    var d = {}; new FormData(form).forEach(function (v, k) { d[k] = v; });
    if (!d.nome || !/^\S+@\S+\.\S+$/.test(d.email || '')) { msg.className = 'msg err'; msg.textContent = T[lang].err_req; return; }
    var btn = $('button[type=submit]', form); btn.disabled = true;
    fetch(body.dataset.contatoUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': $('meta[name=csrf-token]').content }, body: JSON.stringify(d) })
      .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
      .then(function () {
        var t = T[lang], txt = [t.hello.replace('{s}', siteName), d.nome, d.passeio, d.data, d.pessoas ? d.pessoas + ' ' : ''].filter(Boolean).join(' | ');
        msg.className = 'msg ok'; msg.textContent = t.ok;
        if (zap) { var a = document.createElement('a'); a.className = 'btn btn--zap'; a.style.marginTop = '12px'; a.target = '_blank'; a.rel = 'noopener'; a.href = 'https://wa.me/' + zap + '?text=' + encodeURIComponent(txt); a.textContent = t.ok_btn; msg.appendChild(document.createElement('br')); msg.appendChild(a); }
        form.reset();
      })
      .catch(function () { msg.className = 'msg err'; msg.textContent = T[lang].err; })
      .finally(function () { btn.disabled = false; });
  });
})();
</script>
@endverbatim
</body>
</html>
