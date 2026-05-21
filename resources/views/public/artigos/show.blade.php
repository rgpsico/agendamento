<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $conteudo->titulo }} — {{ $nichoAtual?->nome ?? config('app.name') }}</title>
    <meta name="description" content="{{ $conteudo->meta_descricao ?: Str::limit(strip_tags($conteudo->legenda ?: $conteudo->corpo), 160) }}">

    {{-- Open Graph (WhatsApp, Instagram, LinkedIn) --}}
    <meta property="og:type"        content="article">
    <meta property="og:title"       content="{{ $conteudo->titulo }}">
    <meta property="og:description" content="{{ $conteudo->meta_descricao ?: Str::limit(strip_tags($conteudo->legenda ?: $conteudo->corpo), 160) }}">
    <meta property="og:url"         content="{{ $conteudo->url_publica }}">
    @if($conteudo->imagem_capa)
    <meta property="og:image"       content="{{ $conteudo->imagem_capa_url }}">
    @endif
    <meta property="og:site_name"   content="{{ $nichoAtual?->nome ?? config('app.name') }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $conteudo->titulo }}">
    <meta name="twitter:description" content="{{ $conteudo->meta_descricao ?: Str::limit(strip_tags($conteudo->legenda ?: $conteudo->corpo), 160) }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400&family=Work+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary:      #0f766e;
            --primary-dark: #115e59;
            --accent:       #f97316;
            --text:         #0f172a;
            --muted:        #64748b;
            --bg:           #f8fafc;
            --white:        #ffffff;
            --border:       #e2e8f0;
            --shadow:       0 4px 24px rgba(15,23,42,.08);
            --shadow-lg:    0 16px 48px rgba(15,23,42,.14);
        }

        body {
            font-family: 'Work Sans', sans-serif;
            color: var(--text);
            background:
                radial-gradient(1000px 600px at 5% -5%, rgba(249,115,22,.06), transparent 55%),
                radial-gradient(800px 600px at 95% 5%, rgba(15,118,110,.09), transparent 50%),
                var(--bg);
        }

        /* ── Header ── */
        header {
            background: rgba(255,255,255,.92); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100;
        }
        .header-inner {
            max-width: 1000px; margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between; height: 64px;
        }
        .logo {
            font-family: 'Fraunces', serif; font-size: 1.4rem; font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; text-decoration: none;
        }
        .back-link {
            display: inline-flex; align-items: center; gap: .5rem;
            color: var(--muted); text-decoration: none; font-size: .88rem;
            font-weight: 500; transition: color .2s, gap .2s;
        }
        .back-link:hover { color: var(--primary); gap: .8rem; }

        /* ── Artigo ── */
        .artigo-wrap { max-width: 820px; margin: 3rem auto 5rem; padding: 0 2rem; }

        .artigo-card {
            background: var(--white); border-radius: 20px;
            box-shadow: var(--shadow-lg); overflow: hidden;
        }

        .artigo-hero {
            width: 100%; height: 380px;
            background-size: cover; background-position: center;
        }
        .artigo-hero.placeholder {
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, rgba(15,118,110,.15), rgba(249,115,22,.15));
            height: 240px;
        }
        .artigo-hero.placeholder i { font-size: 4rem; opacity: .2; }

        .artigo-body { padding: 3rem; }

        /* Badge nicho */
        .nicho-badge {
            display: inline-block; background: rgba(15,118,110,.1); color: var(--primary);
            font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
            padding: .3rem .9rem; border-radius: 20px; margin-bottom: 1.2rem;
        }

        .artigo-title {
            font-family: 'Fraunces', serif; font-size: clamp(1.8rem, 4vw, 2.7rem);
            font-weight: 700; line-height: 1.18; margin-bottom: 1rem; color: var(--text);
        }

        .artigo-meta {
            display: flex; flex-wrap: wrap; gap: 1.2rem;
            color: var(--muted); font-size: .85rem;
            padding-bottom: 1.5rem; border-bottom: 2px solid var(--border);
            margin-bottom: 2.5rem;
        }
        .artigo-meta span { display: flex; align-items: center; gap: .4rem; }

        /* Compartilhar barra */
        .share-bar {
            display: flex; align-items: center; gap: .8rem; flex-wrap: wrap;
            background: var(--bg); border-radius: 12px; padding: 1rem 1.2rem;
            margin-bottom: 2.5rem; border: 1px solid var(--border);
        }
        .share-bar p { font-size: .82rem; font-weight: 600; color: var(--muted); margin-right: .4rem; }
        .share-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .45rem .9rem; border-radius: 8px; font-size: .8rem;
            font-weight: 600; text-decoration: none; border: none; cursor: pointer;
            transition: opacity .2s, transform .1s;
        }
        .share-btn:hover { opacity: .85; transform: translateY(-1px); }
        .share-btn:active { transform: translateY(0); }
        .btn-whatsapp { background: #25d366; color: #fff; }
        .btn-linkedin  { background: #0077b5; color: #fff; }
        .btn-instagram { background: linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888); color: #fff; }
        .btn-copiar    { background: var(--text); color: #fff; }
        .btn-copiar.copiado { background: var(--primary); }

        /* Conteúdo do artigo */
        .artigo-content {
            font-size: 1.1rem; line-height: 1.9; color: #1e293b;
        }
        .artigo-content p { margin-bottom: 1.5rem; }
        .artigo-content h2 {
            font-family: 'Fraunces', serif; font-size: 1.7rem; font-weight: 700;
            margin-top: 2.5rem; margin-bottom: 1rem; color: var(--text);
            padding-bottom: .5rem; border-bottom: 2px solid var(--border);
        }
        .artigo-content h3 {
            font-family: 'Fraunces', serif; font-size: 1.3rem; font-weight: 600;
            margin-top: 2rem; margin-bottom: .8rem; color: var(--text);
        }
        .artigo-content ul, .artigo-content ol {
            margin: 1rem 0 1.5rem 1.5rem;
        }
        .artigo-content li { margin-bottom: .5rem; }
        .artigo-content blockquote {
            border-left: 4px solid var(--primary); background: rgba(15,118,110,.05);
            padding: 1rem 1.5rem; border-radius: 0 8px 8px 0; margin: 1.5rem 0;
            font-style: italic; color: var(--muted);
        }
        .artigo-content strong { color: var(--text); font-weight: 600; }
        .artigo-content img { max-width: 100%; border-radius: 10px; margin: 1rem 0; }
        .artigo-content a { color: var(--primary); text-decoration: underline; }
        .artigo-content hr { border: none; border-top: 2px solid var(--border); margin: 2rem 0; }

        /* Hashtags */
        .hashtags { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border); }
        .hashtags span {
            display: inline-block; background: rgba(15,118,110,.08); color: var(--primary);
            font-size: .78rem; padding: .25rem .7rem; border-radius: 20px; margin: .2rem;
        }

        /* CTA box */
        .cta-box {
            margin-top: 3rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px; padding: 2.5rem; text-align: center; color: #fff;
        }
        .cta-box h3 { font-family: 'Fraunces', serif; font-size: 1.6rem; margin-bottom: .8rem; }
        .cta-box p { opacity: .85; margin-bottom: 1.5rem; font-size: 1rem; }
        .cta-box a {
            display: inline-block; background: var(--accent); color: #fff;
            padding: .8rem 2rem; border-radius: 10px; font-weight: 700; text-decoration: none;
            font-size: 1rem; transition: opacity .2s;
        }
        .cta-box a:hover { opacity: .9; }

        /* Artigos relacionados */
        .relacionados { max-width: 820px; margin: 0 auto 5rem; padding: 0 2rem; }
        .relacionados h2 {
            font-family: 'Fraunces', serif; font-size: 1.4rem; margin-bottom: 1.5rem; color: var(--text);
        }
        .relacionados-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
        .rel-card {
            background: var(--white); border-radius: 12px; overflow: hidden;
            box-shadow: var(--shadow); text-decoration: none; color: inherit;
            transition: transform .2s, box-shadow .2s;
        }
        .rel-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); }
        .rel-thumb { height: 130px; overflow: hidden; background: linear-gradient(135deg, rgba(15,118,110,.15), rgba(249,115,22,.15)); display: flex; align-items: center; justify-content: center; }
        .rel-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .rel-thumb i { font-size: 2rem; opacity: .25; }
        .rel-body { padding: 1rem; }
        .rel-titulo { font-weight: 700; font-size: .88rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .rel-meta { color: var(--muted); font-size: .75rem; margin-top: .5rem; }

        /* Footer */
        footer {
            background: var(--text); color: rgba(255,255,255,.55);
            text-align: center; padding: 2.5rem 1rem; font-size: .85rem;
        }
        footer a { color: rgba(255,255,255,.8); text-decoration: none; }
        footer strong { color: #fff; }

        @media (max-width: 768px) {
            .artigo-body { padding: 2rem 1.5rem; }
            .artigo-title { font-size: 1.8rem; }
            .artigo-content { font-size: 1rem; }
        }
    </style>
</head>
<body>

<header>
    <div class="header-inner">
        <a href="{{ route('artigos.index') }}" class="logo">{{ $nichoAtual?->nome ?? config('app.name') }}</a>
        <a href="{{ route('artigos.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Ver todos os artigos
        </a>
    </div>
</header>

<div class="artigo-wrap">
    <article class="artigo-card">

        {{-- Imagem de capa --}}
        @if($conteudo->imagem_capa)
            <div class="artigo-hero"
                 style="background-image: url('{{ $conteudo->imagem_capa_url }}');"></div>
        @else
            <div class="artigo-hero placeholder">
                <i class="fas fa-newspaper"></i>
            </div>
        @endif

        <div class="artigo-body">

            {{-- Nicho badge --}}
            @if($conteudo->nicho)
                <span class="nicho-badge">{{ ucfirst($conteudo->nicho) }}</span>
            @endif

            {{-- Título --}}
            <h1 class="artigo-title">{{ $conteudo->titulo }}</h1>

            {{-- Meta --}}
            <div class="artigo-meta">
                <span><i class="far fa-calendar"></i>{{ $conteudo->created_at->format('d \d\e F \d\e Y') }}</span>
                <span><i class="far fa-clock"></i>{{ $conteudo->tempo_leitura }}</span>
                @if($conteudo->palavras_count)
                    <span><i class="fas fa-align-left"></i>{{ number_format($conteudo->palavras_count) }} palavras</span>
                @endif
                @if($conteudo->autor)
                    <span><i class="fas fa-user"></i>{{ $conteudo->autor }}</span>
                @endif
            </div>

            {{-- Barra de compartilhamento --}}
            <div class="share-bar">
                <p>Compartilhar:</p>
                <a href="https://wa.me/?text={{ urlencode($conteudo->titulo . "\n\n" . $conteudo->url_publica) }}"
                   target="_blank" class="share-btn btn-whatsapp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($conteudo->url_publica) }}"
                   target="_blank" class="share-btn btn-linkedin">
                    <i class="fab fa-linkedin"></i> LinkedIn
                </a>
                <a href="https://www.instagram.com/" target="_blank" class="share-btn btn-instagram">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <button class="share-btn btn-copiar" id="btnCopiarLink"
                        data-link="{{ $conteudo->url_publica }}">
                    <i class="fas fa-copy"></i> Copiar link
                </button>
            </div>

            {{-- Corpo do artigo --}}
            <div class="artigo-content">
                {!! $conteudo->corpo !!}
            </div>

            {{-- Hashtags --}}
            @if($conteudo->hashtags)
            <div class="hashtags">
                @foreach(explode(' ', $conteudo->hashtags) as $tag)
                    @if(trim($tag))
                        <span>{{ trim($tag) }}</span>
                    @endif
                @endforeach
            </div>
            @endif

            {{-- CTA --}}
            <div class="cta-box">
                <h3>Transforme seu estúdio com tecnologia</h3>
                <p>Veja como o {{ $nichoAtual?->nome ?? config('app.name') }} pode automatizar sua gestão, reduzir faltas e aumentar sua receita.</p>
                <a href="/">
                    Conheça o sistema gratuito por 14 dias →
                </a>
            </div>

        </div>
    </article>
</div>

{{-- Artigos relacionados --}}
@if($relacionados->isNotEmpty())
<section class="relacionados">
    <h2>Artigos relacionados</h2>
    <div class="relacionados-grid">
        @foreach($relacionados as $rel)
        <a href="{{ route('artigos.show', $rel->slug) }}" class="rel-card">
            <div class="rel-thumb">
                @if($rel->imagem_capa)
                    <img src="{{ $rel->imagem_capa_url }}" alt="{{ $rel->titulo }}">
                @else
                    <i class="fas fa-newspaper"></i>
                @endif
            </div>
            <div class="rel-body">
                <div class="rel-titulo">{{ $rel->titulo }}</div>
                <div class="rel-meta">{{ $rel->created_at->format('d/m/Y') }} · {{ $rel->tempo_leitura }}</div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

<footer>
    <p>&copy; {{ date('Y') }} <strong>{{ $nichoAtual?->nome ?? config('app.name') }}</strong>
        · <a href="{{ route('artigos.index') }}">Blog</a>
        · <a href="/">Sistema de Gestão</a>
    </p>
</footer>

<script>
document.getElementById('btnCopiarLink').addEventListener('click', function() {
    navigator.clipboard.writeText(this.dataset.link).then(() => {
        this.classList.add('copiado');
        this.innerHTML = '<i class="fas fa-check"></i> Link copiado!';
        setTimeout(() => {
            this.classList.remove('copiado');
            this.innerHTML = '<i class="fas fa-copy"></i> Copiar link';
        }, 2500);
    });
});

// Animação suave no scroll
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.rel-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity .4s ease, transform .4s ease';
    observer.observe(el);
});
</script>
</body>
</html>
