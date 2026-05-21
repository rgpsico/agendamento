<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog — {{ $nichoAtual?->nome ?? config('app.name') }}</title>
    <meta name="description" content="Artigos e dicas sobre gestão para o seu negócio.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --shadow-lg:    0 12px 40px rgba(15,23,42,.13);
        }

        body { font-family: 'Work Sans', sans-serif; background: var(--bg); color: var(--text); }

        /* ── Header ── */
        header {
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100;
        }
        .header-inner {
            max-width: 1140px; margin: 0 auto; padding: 0 2rem;
            display: flex; align-items: center; justify-content: space-between;
            height: 68px;
        }
        .logo {
            font-family: 'Fraunces', serif; font-size: 1.5rem; font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; text-decoration: none;
        }
        .logo small { font-size: .65rem; display: block; letter-spacing: .08em; text-transform: uppercase; -webkit-text-fill-color: var(--muted); }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: .9rem; font-weight: 500; transition: color .2s; }
        .nav-links a:hover { color: var(--primary); }
        .btn-cta {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #fff !important; padding: .5rem 1.2rem; border-radius: 8px;
            font-weight: 600 !important; font-size: .85rem !important;
        }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(135deg, rgba(15,118,110,.07), rgba(249,115,22,.07));
            padding: 5rem 2rem 4rem;
            text-align: center;
        }
        .hero-badge {
            display: inline-block; background: rgba(15,118,110,.1); color: var(--primary);
            font-size: .75rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            padding: .3rem .9rem; border-radius: 20px; margin-bottom: 1.2rem;
        }
        .hero h1 {
            font-family: 'Fraunces', serif; font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 700; line-height: 1.15; margin-bottom: 1rem;
        }
        .hero p { color: var(--muted); font-size: 1.1rem; max-width: 560px; margin: 0 auto; }

        /* ── Grid de artigos ── */
        .artigos-section { max-width: 1140px; margin: 0 auto; padding: 3rem 2rem 5rem; }

        .artigos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 28px; }

        .artigo-card {
            background: var(--white); border-radius: 16px; overflow: hidden;
            box-shadow: var(--shadow); transition: box-shadow .25s, transform .25s;
            display: flex; flex-direction: column; text-decoration: none; color: inherit;
        }
        .artigo-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); }

        .card-thumb {
            height: 200px; overflow: hidden; background: linear-gradient(135deg, rgba(15,118,110,.2), rgba(249,115,22,.2));
            display: flex; align-items: center; justify-content: center;
        }
        .card-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
        .artigo-card:hover .card-thumb img { transform: scale(1.04); }
        .card-thumb-icon { font-size: 2.5rem; opacity: .3; }

        .card-body { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
        .card-badge {
            display: inline-block; font-size: .68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .06em;
            background: rgba(15,118,110,.1); color: var(--primary);
            padding: .2rem .7rem; border-radius: 20px; margin-bottom: .8rem;
        }
        .card-titulo {
            font-family: 'Fraunces', serif; font-size: 1.2rem; font-weight: 700;
            line-height: 1.3; margin-bottom: .7rem;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .card-legenda {
            color: var(--muted); font-size: .88rem; line-height: 1.55;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
            flex: 1;
        }
        .card-footer {
            padding: 1rem 1.5rem; border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-meta { color: var(--muted); font-size: .78rem; display: flex; gap: 1rem; }
        .card-link {
            font-size: .82rem; font-weight: 600; color: var(--primary);
            text-decoration: none; display: flex; align-items: center; gap: .4rem;
        }
        .card-link:hover { gap: .7rem; }

        /* ── Paginação ── */
        .paginacao { margin-top: 3rem; display: flex; justify-content: center; }
        .paginacao nav { display: flex; gap: .5rem; }

        /* ── Empty state ── */
        .empty { text-align: center; padding: 6rem 2rem; color: var(--muted); }
        .empty i { font-size: 3rem; opacity: .2; display: block; margin-bottom: 1rem; }

        /* ── Footer ── */
        footer {
            background: var(--text); color: rgba(255,255,255,.6);
            text-align: center; padding: 2.5rem 1rem;
            font-size: .85rem;
        }
        footer a { color: rgba(255,255,255,.8); text-decoration: none; }
        footer strong { color: #fff; }

        @media (max-width: 640px) {
            .nav-links { display: none; }
            .artigos-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<header>
    <div class="header-inner">
        <a href="{{ route('artigos.index') }}" class="logo">
            {{ $nichoAtual?->nome ?? config('app.name') }}
            <small>Blog & Conteúdo</small>
        </a>
        <nav class="nav-links">
            <a href="/">Início</a>
            <a href="{{ route('artigos.index') }}">Blog</a>
            @if($nichoAtual?->whatsapp)
                <a href="https://wa.me/55{{ preg_replace('/\D/', '', $nichoAtual->whatsapp) }}" class="btn-cta" target="_blank">
                    Falar com a equipe
                </a>
            @endif
        </nav>
    </div>
</header>

{{-- Hero --}}
<section class="hero">
    <span class="hero-badge">📰 Blog</span>
    <h1>Conteúdo para<br>fazer seu negócio crescer</h1>
    <p>Dicas práticas, estratégias e novidades sobre gestão de estúdios e escolas.</p>
</section>

{{-- Artigos --}}
<section class="artigos-section">

    @if($artigos->isEmpty())
        <div class="empty">
            <i class="fas fa-newspaper"></i>
            <p>Nenhum artigo publicado ainda. Volte em breve!</p>
        </div>
    @else
        <div class="artigos-grid">
            @foreach($artigos as $artigo)
            <a href="{{ route('artigos.show', $artigo->slug) }}" class="artigo-card">
                <div class="card-thumb">
                    @if($artigo->imagem_capa)
                        <img src="{{ $artigo->imagem_capa_url }}" alt="{{ $artigo->titulo }}">
                    @else
                        <i class="fas fa-newspaper card-thumb-icon"></i>
                    @endif
                </div>
                <div class="card-body">
                    @if($artigo->nicho)
                        <span class="card-badge">{{ ucfirst($artigo->nicho) }}</span>
                    @endif
                    <div class="card-titulo">{{ $artigo->titulo }}</div>
                    @if($artigo->legenda)
                        <div class="card-legenda">{{ $artigo->legenda }}</div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="card-meta">
                        <span><i class="far fa-calendar me-1"></i>{{ $artigo->created_at->format('d/m/Y') }}</span>
                        <span><i class="far fa-clock me-1"></i>{{ $artigo->tempo_leitura }}</span>
                    </div>
                    <span class="card-link">Ler artigo →</span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="paginacao">
            {{ $artigos->links() }}
        </div>
    @endif

</section>

<footer>
    <p>&copy; {{ date('Y') }} <strong>{{ $nichoAtual?->nome ?? config('app.name') }}</strong> — Todos os direitos reservados.</p>
</footer>

</body>
</html>
