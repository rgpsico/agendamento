<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'PilatesGestão')</title>
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a67d8;
            --secondary: #764ba2;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-light: linear-gradient(135deg, #f0f4ff 0%, #f5f0ff 100%);
            --text-dark: #1a202c;
            --text-gray: #4a5568;
            --text-light: #718096;
            --border: #e2e8f0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; color: var(--text-dark); background: #fff; }

        .navbar-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 0; position: sticky; top: 0; z-index: 1000;
        }
        .navbar-brand-text {
            font-size: 1.5rem; font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .btn-back { color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: opacity 0.2s; }
        .btn-back:hover { opacity: 0.7; color: var(--primary); }

        .legal-hero {
            background: var(--gradient); padding: 4rem 0 3rem; color: #fff;
            position: relative; overflow: hidden;
        }
        .legal-hero::before {
            content: ''; position: absolute; top: -40%; right: -10%;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.07); border-radius: 50%;
        }
        .legal-hero h1 { font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; margin-bottom: 0.5rem; position: relative; }
        .legal-hero p { font-size: 0.95rem; opacity: 0.85; position: relative; }

        .legal-content {
            max-width: 820px; margin: 0 auto;
            padding: 3.5rem 1.5rem 5rem;
            color: var(--text-gray); line-height: 1.75;
        }
        .legal-content h2 {
            font-size: 1.25rem; font-weight: 700; color: var(--text-dark);
            margin: 2.5rem 0 1rem; padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--gradient-light);
        }
        .legal-content h2:first-child { margin-top: 0; }
        .legal-content h3 { font-size: 1rem; font-weight: 700; color: var(--text-dark); margin: 1.5rem 0 0.5rem; }
        .legal-content p { font-size: 0.95rem; margin-bottom: 1rem; }
        .legal-content ul, .legal-content ol { margin: 0 0 1rem 1.5rem; font-size: 0.95rem; }
        .legal-content ul li, .legal-content ol li { margin-bottom: 0.4rem; }
        .legal-content strong { color: var(--text-dark); font-weight: 600; }
        .legal-content a { color: var(--primary); text-decoration: none; }
        .legal-content a:hover { text-decoration: underline; }
        .legal-content table { width: 100%; border-collapse: collapse; margin: 1rem 0 1.5rem; font-size: 0.88rem; }
        .legal-content table th, .legal-content table td { padding: 0.75rem 1rem; border: 1px solid var(--border); text-align: left; }
        .legal-content table th { background: var(--gradient-light); color: var(--text-dark); font-weight: 600; }
        .legal-content .placeholder-tag {
            background: #fff3cd; color: #856404;
            padding: 0.1rem 0.4rem; border-radius: 4px;
            font-family: monospace; font-size: 0.85em; font-weight: 600;
        }
        .legal-content .update-info {
            background: var(--gradient-light); border-left: 4px solid var(--primary);
            padding: 1rem 1.25rem; border-radius: 8px;
            margin-bottom: 2rem; font-size: 0.88rem;
        }
        .legal-content .update-info strong { color: var(--primary); }
        .legal-content .nav-legal {
            display: flex; gap: 1rem; flex-wrap: wrap;
            margin-bottom: 2.5rem; padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }
        .legal-content .nav-legal a {
            font-size: 0.85rem; font-weight: 600;
            padding: 0.4rem 1rem; border-radius: 50px;
            border: 1px solid var(--border); transition: all 0.2s;
        }
        .legal-content .nav-legal a:hover {
            background: var(--gradient-light); border-color: var(--primary); text-decoration: none;
        }
        .legal-content .nav-legal a.active {
            background: var(--gradient); color: #fff; border-color: transparent;
        }

        footer { background: #1a202c; color: rgba(255,255,255,0.7); padding: 2.5rem 0 1.5rem; text-align: center; }
        footer .brand { color: #fff; font-size: 1.2rem; font-weight: 800; margin-bottom: 0.5rem; }
        footer .brand span { background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        footer .links { margin: 1rem 0; }
        footer .links a { color: rgba(255,255,255,0.6); text-decoration: none; margin: 0 0.75rem; font-size: 0.85rem; transition: color 0.2s; }
        footer .links a:hover { color: var(--primary); }
        footer .copy { font-size: 0.8rem; color: rgba(255,255,255,0.4); margin-top: 1rem; }
    </style>
</head>
<body>

<nav class="navbar-custom">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <i class="fa-solid fa-calendar-check" style="color: var(--primary); font-size: 1.4rem;"></i>
            <span class="navbar-brand-text">PilatesGestão</span>
        </a>
        <a href="{{ url('/') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left me-1"></i> Voltar para o site
        </a>
    </div>
</nav>

<section class="legal-hero">
    <div class="container">
        <h1>@yield('page_title')</h1>
        <p>@yield('page_subtitle')</p>
    </div>
</section>

<main class="legal-content">
    <div class="nav-legal">
        <a href="{{ url('/termos') }}" class="@yield('nav_termos')">Termos de Uso</a>
        <a href="{{ url('/privacidade') }}" class="@yield('nav_privacidade')">Política de Privacidade</a>
        <a href="{{ url('/lgpd') }}" class="@yield('nav_lgpd')">LGPD</a>
    </div>

    @yield('content')
</main>

<footer>
    <div class="container">
        <div class="brand"><i class="fa-solid fa-calendar-check me-2"></i><span>PilatesGestão</span></div>
        <div class="links">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/termos') }}">Termos</a>
            <a href="{{ url('/privacidade') }}">Privacidade</a>
            <a href="{{ url('/lgpd') }}">LGPD</a>
            <a href="mailto:contato@pilatesgestao.com.br">Contato</a>
        </div>
        <div class="copy">© {{ date('Y') }} PilatesGestão. Todos os direitos reservados.</div>
    </div>
</footer>

</body>
</html>
