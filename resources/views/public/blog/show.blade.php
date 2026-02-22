<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $artigo->titulo }} - Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Work+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            font-family: "Fraunces", serif;
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        main {
            max-width: 860px;
            margin: 3.5rem auto 4rem;
            padding: 0 2rem;
        }

        .article-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .article-hero {
            width: 100%;
            height: 380px;
            background-size: cover;
            background-position: center;
        }

        .article-hero.placeholder {
            background: linear-gradient(120deg, rgba(15, 118, 110, 0.25), rgba(249, 115, 22, 0.25));
        }

        .article-body {
            padding: 3rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1.5rem;
            transition: gap 0.3s;
        }

        .back-button:hover {
            gap: 1rem;
        }

        .article-title {
            font-family: "Fraunces", serif;
            font-size: 2.4rem;
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.85;
            color: var(--text-dark);
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content h2,
        .article-content h3 {
            margin-top: 2.2rem;
            margin-bottom: 1rem;
            font-family: "Fraunces", serif;
        }

        footer {
            text-align: center;
            color: rgba(15, 23, 42, 0.6);
            font-size: 0.9rem;
            padding: 2rem 1rem 3rem;
        }

        @media (max-width: 768px) {
            .article-body {
                padding: 2rem;
            }

            .article-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <a href="{{ route('public.blog.index') }}" class="logo">Blog Moderno</a>
            <a href="{{ route('public.blog.index') }}" class="back-button">Voltar para a lista</a>
        </div>
    </header>

    <main>
        <article class="article-card">
            @if ($artigo->imagem_capa)
                <div class="article-hero" style="background-image: url('{{ asset('storage/' . $artigo->imagem_capa) }}');"></div>
            @else
                <div class="article-hero placeholder"></div>
            @endif
            <div class="article-body">
                <a href="{{ route('public.blog.index') }}" class="back-button">← Voltar</a>
                <h1 class="article-title">{{ $artigo->titulo }}</h1>
                <div class="article-meta">
                    <span>{{ $artigo->publicado_em ? $artigo->publicado_em->format('d/m/Y') : $artigo->created_at->format('d/m/Y') }}</span>
                    <span>Leitura rapida</span>
                </div>
                <div class="article-content">
                    {!! $artigo->conteudo !!}
                </div>
            </div>
        </article>
    </main>

    <footer>
        &copy; {{ date('Y') }} Blog Moderno
    </footer>

    <script>
        window.addEventListener("load", () => {
            gsap.from("header", {
                y: -50,
                opacity: 0,
                duration: 0.7,
                ease: "power3.out"
            });

            gsap.from(".article-card", {
                y: 40,
                opacity: 0,
                duration: 0.7,
                ease: "power3.out"
            });
        });
    </script>
</body>
</html>
