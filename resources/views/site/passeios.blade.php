@php
    $zap = preg_replace('/\D/', '', $site->whatsapp ?? '');
    $zapUrl = $zap ? 'https://wa.me/' . $zap . '?text=' . rawurlencode('Olá! Vi o site da ' . $site->titulo . ' e gostaria de saber mais sobre os passeios.') : null;
    $capaUrl = $site->capa ? asset('storage/' . $site->capa) : null;
    $sobreUrl = $site->sobre_imagem ? asset('storage/' . $site->sobre_imagem) : null;
    $galeria = collect(\Illuminate\Support\Facades\Storage::disk('public')->files('sites/galeria/' . $site->id))->sort()->values();
    $passeios = $site->servicos ?? collect();
    $temSobre = filled($site->sobre_titulo) || filled($site->sobre_descricao) || !empty($site->sobre_itens);
    $duracao = function ($min) {
        if (!$min) return null;
        $h = intdiv((int) $min, 60); $m = (int) $min % 60;
        return $h ? $h . 'h' . ($m ? sprintf('%02d', $m) : '') : $m . ' min';
    };
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $site->titulo }}</title>
    <meta name="description" content="{{ $site->descricao }}">
    @unless($site->ativo)<meta name="robots" content="noindex, nofollow">@endunless
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $site->titulo }}">
    <meta property="og:description" content="{{ $site->descricao }}">
    @if($capaUrl)<meta property="og:image" content="{{ $capaUrl }}">@endif
    <style>
        :root { --ink:#10242b; --muted:#506068; --bg:#f6f3ec; --card:#fff; --teal:#0b6e6e; --teal-dark:#074f52; --sun:#f5b301; --line:#e4dfd2; }
        * { box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body { margin:0; font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; color:var(--ink); background:var(--bg); line-height:1.55; }
        a { color:inherit; }
        .wrap { width:min(1080px,100% - 32px); margin-inline:auto; }
        header.top { position:sticky; top:0; z-index:20; background:rgba(246,243,236,.94); backdrop-filter:blur(8px); border-bottom:1px solid var(--line); }
        header.top .wrap { display:flex; align-items:center; justify-content:space-between; gap:12px; padding-block:12px; }
        .brand { font-weight:800; letter-spacing:-.01em; text-decoration:none; }
        nav.links { display:flex; gap:18px; font-size:.95rem; }
        nav.links a { text-decoration:none; color:var(--muted); }
        nav.links a:hover { color:var(--ink); }
        .btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 20px; border-radius:999px; font-weight:700; text-decoration:none; border:0; cursor:pointer; background:var(--sun); color:#1b1500; }
        .btn:hover { filter:brightness(.96); }
        .btn--zap { background:#1fa855; color:#fff; }
        .hero { position:relative; color:#fff; background:var(--teal-dark) center/cover no-repeat; }
        .hero::before { content:""; position:absolute; inset:0; background:linear-gradient(180deg,rgba(7,30,34,.35),rgba(7,30,34,.8)); }
        .hero .wrap { position:relative; padding-block:clamp(72px,16vw,160px) clamp(48px,9vw,96px); max-width:760px; margin-left:max(16px,calc((100% - 1080px)/2)); }
        .hero h1 { font-size:clamp(2rem,6vw,3.6rem); line-height:1.05; margin:0 0 16px; letter-spacing:-.02em; text-wrap:balance; }
        .hero p { font-size:clamp(1.05rem,2.4vw,1.25rem); margin:0 0 28px; opacity:.95; max-width:56ch; }
        section { padding-block:clamp(48px,8vw,88px); }
        h2 { font-size:clamp(1.6rem,4vw,2.3rem); margin:0 0 28px; letter-spacing:-.01em; text-wrap:balance; }
        .passeios { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px; }
        .passeio { background:var(--card); border:1px solid var(--line); border-radius:18px; padding:24px; display:flex; flex-direction:column; gap:10px; }
        .passeio h3 { margin:0; font-size:1.2rem; }
        .passeio p { margin:0; color:var(--muted); }
        .meta { display:flex; gap:10px; flex-wrap:wrap; font-size:.9rem; color:var(--teal-dark); font-weight:700; }
        .meta span { background:#e3f1ef; padding:4px 10px; border-radius:999px; }
        .passeio .btn { margin-top:auto; }
        .galeria { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
        .galeria img { width:100%; aspect-ratio:4/5; object-fit:cover; border-radius:14px; display:block; background:var(--line); }
        .sobre { background:var(--card); }
        .sobre .grid { display:grid; gap:32px; align-items:center; }
        .sobre img { width:100%; border-radius:18px; display:block; }
        .sobre p { color:var(--muted); font-size:1.05rem; }
        .itens { display:grid; gap:14px; margin-top:20px; }
        .itens div { border-left:4px solid var(--sun); padding-left:14px; }
        .itens strong { display:block; }
        .cta { background:var(--teal-dark); color:#fff; text-align:center; }
        .cta p { max-width:52ch; margin:0 auto 24px; opacity:.92; }
        footer { padding-block:28px; text-align:center; color:var(--muted); font-size:.9rem; }
        .zap-fab { position:fixed; right:16px; bottom:calc(16px + env(safe-area-inset-bottom,0px)); z-index:30; width:58px; height:58px; border-radius:50%; background:#1fa855; display:grid; place-items:center; box-shadow:0 8px 24px rgba(0,0,0,.25); }
        .zap-fab svg { width:30px; height:30px; fill:#fff; }
        @media (min-width:760px) {
            .galeria { grid-template-columns:repeat(3,1fr); }
            .sobre .grid { grid-template-columns:1fr 1fr; }
        }
        @media (max-width:560px) { nav.links { display:none; } }
    </style>
</head>
<body>
<header class="top">
    <div class="wrap">
        <a class="brand" href="#">{{ $site->titulo }}</a>
        <nav class="links">
            @if($passeios->count())<a href="#passeios">Passeios</a>@endif
            @if($galeria->count())<a href="#fotos">Fotos</a>@endif
            @if($temSobre)<a href="#sobre">Sobre</a>@endif
            @if($zapUrl)<a href="#contato">Contato</a>@endif
        </nav>
        @if($zapUrl)<a class="btn btn--zap" href="{{ $zapUrl }}" target="_blank" rel="noopener">WhatsApp</a>@endif
    </div>
</header>

<div class="hero" @if($capaUrl) style="background-image:url('{{ $capaUrl }}')" @endif>
    <div class="wrap">
        <h1>{{ $site->titulo }}</h1>
        @if($site->descricao)<p>{{ $site->descricao }}</p>@endif
        @if($zapUrl)<a class="btn" href="{{ $zapUrl }}" target="_blank" rel="noopener">Reservar pelo WhatsApp</a>@endif
    </div>
</div>

@if($passeios->count())
<section id="passeios">
    <div class="wrap">
        <h2>Nossos passeios</h2>
        <div class="passeios">
            @foreach($passeios as $p)
                <article class="passeio">
                    <h3>{{ $p->titulo }}</h3>
                    @if($p->descricao)<p>{{ $p->descricao }}</p>@endif
                    <div class="meta">
                        @if($duracao($p->tempo_de_aula))<span>{{ $duracao($p->tempo_de_aula) }}</span>@endif
                        @if((float) $p->preco > 0)<span>R$ {{ number_format((float) $p->preco, 2, ',', '.') }}</span>@endif
                    </div>
                    @if($zapUrl)
                        <a class="btn btn--zap" target="_blank" rel="noopener"
                           href="https://wa.me/{{ $zap }}?text={{ rawurlencode('Olá! Quero reservar o passeio: ' . $p->titulo) }}">Reservar</a>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($galeria->count())
<section id="fotos" style="padding-top:0">
    <div class="wrap">
        <h2>Momentos dos passeios</h2>
        <div class="galeria">
            @foreach($galeria as $foto)
                <img src="{{ asset('storage/' . $foto) }}" alt="Foto de um passeio da {{ $site->titulo }}" loading="lazy">
            @endforeach
        </div>
    </div>
</section>
@endif

@if($temSobre)
<section id="sobre" class="sobre">
    <div class="wrap grid">
        <div>
            @if($site->sobre_titulo)<h2>{{ $site->sobre_titulo }}</h2>@endif
            @if($site->sobre_descricao)<p>{{ $site->sobre_descricao }}</p>@endif
            @if(!empty($site->sobre_itens))
                <div class="itens">
                    @foreach($site->sobre_itens as $item)
                        <div><strong>{{ $item['titulo'] ?? '' }}</strong>{{ $item['descricao'] ?? '' }}</div>
                    @endforeach
                </div>
            @endif
        </div>
        @if($sobreUrl)<img src="{{ $sobreUrl }}" alt="{{ $site->titulo }}" loading="lazy">@endif
    </div>
</section>
@endif

@if($zapUrl)
<section id="contato" class="cta">
    <div class="wrap">
        <h2>Vamos montar o seu passeio?</h2>
        <p>Chame no WhatsApp, conte a data e quantas pessoas vão, e a gente responde com as opções.</p>
        <a class="btn btn--zap" href="{{ $zapUrl }}" target="_blank" rel="noopener">Chamar no WhatsApp</a>
    </div>
</section>
<a class="zap-fab" href="{{ $zapUrl }}" target="_blank" rel="noopener" aria-label="Chamar no WhatsApp">
    <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3C9 3 3.4 8.6 3.4 15.5c0 2.4.7 4.7 1.9 6.6L3 29l7.1-2.2a12.600 12.600 0 0 0 5.900 1.500c7 0 12.600-5.600 12.600-12.500S23 3 16 3zm0 22.800c-1.900 0-3.700-.5-5.300-1.500l-.4-.2-4.200 1.300 1.400-4-.3-.4a10.200 10.200 0 0 1-1.600-5.500C5.600 9.900 10.300 5.300 16 5.300s10.400 4.600 10.400 10.200S21.700 25.800 16 25.800zm5.700-7.600c-.3-.2-1.800-.9-2.100-1-.3-.1-.5-.2-.7.200s-.8 1-1 1.200c-.2.200-.4.200-.7.100a8.400 8.400 0 0 1-4.200-3.700c-.3-.6.300-.5.900-1.700.1-.2 0-.4 0-.5l-1-2.300c-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.100-.8.400s-1 1-1 2.500 1.100 2.900 1.200 3.100c.2.200 2.100 3.300 5.200 4.500 2.500 1 3 .8 3.500.7.600-.1 1.800-.7 2-1.400.3-.7.3-1.300.2-1.400-.1-.1-.3-.2-.6-.4z"/></svg>
</a>
@endif

<footer>
    <div class="wrap">© {{ date('Y') }} {{ $site->titulo }}</div>
</footer>
</body>
</html>
