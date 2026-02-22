<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog {{ $site?->titulo ? '- ' . $site->titulo : '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Work+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0f766e;
            --primary-dark: #115e59;
            --accent: #f97316;
            --text-dark: #0f172a;
            --text-light: #475569;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
            --shadow-hover: 0 22px 48px rgba(15, 23, 42, 0.18);
        }

        body {
            font-family: "Work Sans", sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background:
                radial-gradient(1200px 800px at 10% -10%, rgba(249, 115, 22, 0.08), transparent 60%),
                radial-gradient(900px 700px at 90% 0%, rgba(15, 118, 110, 0.12), transparent 55%),
                var(--bg-light);
        }

        header {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 1.25rem 0;
            backdrop-filter: blur(10px);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            font-family: "Fraunces", serif;
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        nav a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }

        nav a::after {
            content: "";
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s;
        }

        nav a:hover::after {
            width: 100%;
        }

        nav a:hover {
            color: var(--accent);
        }

        .hero {
            margin-top: 0;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: var(--white);
            padding: 6rem 2rem 4rem;
            text-align: center;
        }

        .hero h1 {
            font-family: "Fraunces", serif;
            font-size: 3.2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 640px;
            margin: 0 auto;
        }

        main {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
        }

        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2.2rem;
            margin-bottom: 3rem;
        }

        .article-card {
            background: var(--white);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
        }

        .article-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .article-image {
            width: 100%;
            height: 220px;
            background-size: cover;
            background-position: center;
            transition: transform 0.4s;
        }

        .article-image.placeholder {
            background: linear-gradient(120deg, rgba(15, 118, 110, 0.2), rgba(249, 115, 22, 0.2));
        }

        .article-card:hover .article-image {
            transform: scale(1.06);
        }

        .article-content {
            padding: 1.6rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .article-date {
            color: var(--text-light);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .article-title {
            font-family: "Fraunces", serif;
            font-size: 1.5rem;
            color: var(--text-dark);
            font-weight: 700;
            line-height: 1.3;
        }

        .article-description {
            color: var(--text-light);
            font-size: 1rem;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: gap 0.3s;
        }

        .read-more:hover {
            gap: 1rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-light);
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        footer {
            background: #0b1120;
            color: var(--white);
            padding: 3rem 2rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-family: "Fraunces", serif;
            font-size: 1.2rem;
        }

        .footer-section p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
            display: inline-block;
            margin-bottom: 0.4rem;
        }

        .footer-links a:hover {
            color: var(--white);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.4rem;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            nav ul {
                flex-direction: column;
                gap: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <a href="{{ route('public.blog.index') }}" class="logo">Blog Moderno</a>
            <nav>
                <ul>
                    <li><a href="{{ route('public.blog.index') }}">Inicio</a></li>
                    <li><a href="#footer">Contato</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <h1>Conteudos para inspirar</h1>
        <p>Leia novidades, dicas e artigos feitos para o seu publico.</p>
    </section>

    <main>
        @if ($artigos->count() === 0)
            <div class="empty-state">Nenhum artigo publicado ate o momento.</div>
        @else
            <div class="articles-grid">
                @foreach ($artigos as $artigo)
                    <a href="{{ route('public.blog.show', $artigo->slug) }}" class="article-card">
                        @if ($artigo->imagem_capa)
                            <div class="article-image" style="background-image: url('{{ asset('storage/' . $artigo->imagem_capa) }}');"></div>
                        @else
                            <div class="article-image placeholder"></div>
                        @endif
                        <div class="article-content">
                            <div class="article-date">
                                {{ $artigo->publicado_em ? $artigo->publicado_em->format('d/m/Y') : $artigo->created_at->format('d/m/Y') }}
                            </div>
                            <h2 class="article-title">{{ $artigo->titulo }}</h2>
                            <p class="article-description">{{ \Illuminate\Support\Str::limit($artigo->resumo ?? strip_tags($artigo->conteudo), 120) }}</p>
                            <span class="read-more">Ler mais</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="pagination">
            {{ $artigos->links() }}
        </div>
    </main>

    <footer id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Blog Moderno</h3>
                <p>Conteudo atualizado para inspirar suas decisoes e ideias.</p>
            </div>
            <div class="footer-section">
                <h3>Links Rapidos</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('public.blog.index') }}">Inicio</a></li>
                    <li><a href="#footer">Contato</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Categorias</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('public.blog.index') }}">Tecnologia</a></li>
                    <li><a href="{{ route('public.blog.index') }}">Design</a></li>
                    <li><a href="{{ route('public.blog.index') }}">Negocios</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Blog Moderno. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        window.addEventListener("load", () => {
            gsap.from("header", {
                y: -60,
                opacity: 0,
                duration: 0.8,
                ease: "power3.out"
            });

            gsap.from(".hero h1", {
                y: 30,
                opacity: 0,
                duration: 0.8,
                delay: 0.1,
                ease: "power3.out"
            });

            gsap.from(".hero p", {
                y: 20,
                opacity: 0,
                duration: 0.8,
                delay: 0.2,
                ease: "power3.out"
            });

            gsap.from(".article-card", {
                y: 60,
                opacity: 0,
                duration: 0.7,
                stagger: 0.12,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: ".articles-grid",
                    start: "top 80%"
                }
            });
        });
    </script>
</body>
</html>
