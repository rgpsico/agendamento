<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AgendaPro — Plataforma Completa de Agendamento e Gestão</title>
    <meta name="description" content="Gerencie agendamentos, alunos, pagamentos e muito mais em uma única plataforma. Ideal para escolas, estúdios e prestadores de serviço.">
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
            --radius: 16px;
            --shadow: 0 8px 32px rgba(102, 126, 234, 0.15);
            --shadow-hover: 0 16px 48px rgba(102, 126, 234, 0.25);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; color: var(--text-dark); background: #fff; }

        /* NAVBAR */
        .navbar-custom {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: box-shadow 0.3s;
        }
        .navbar-custom.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .navbar-brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-link-custom {
            color: var(--text-gray) !important;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .nav-link-custom:hover { color: var(--primary) !important; }
        .btn-nav-login {
            color: var(--primary) !important;
            font-weight: 600;
            border: 2px solid var(--primary);
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-nav-login:hover { background: var(--primary); color: #fff !important; }
        .btn-nav-cta {
            background: var(--gradient);
            color: #fff !important;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.4rem 1.4rem;
            font-size: 0.85rem;
            border: none;
            transition: all 0.2s;
        }
        .btn-nav-cta:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(102,126,234,0.4); }

        /* HERO */
        .hero {
            background: var(--gradient);
            padding: 6rem 0 4rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 700px;
            height: 700px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            margin-bottom: 1.5rem;
            letter-spacing: 0.5px;
        }
        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1.25rem;
        }
        .hero p.lead {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.88);
            line-height: 1.7;
            max-width: 520px;
        }
        .hero-btns { margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn-hero-primary {
            background: #fff;
            color: var(--primary);
            font-weight: 700;
            border-radius: 50px;
            padding: 0.85rem 2rem;
            border: none;
            font-size: 1rem;
            transition: all 0.25s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,0.2); color: var(--primary-dark); }
        .btn-hero-outline {
            background: transparent;
            color: #fff;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.85rem 2rem;
            border: 2px solid rgba(255,255,255,0.6);
            font-size: 1rem;
            transition: all 0.25s;
        }
        .btn-hero-outline:hover { background: rgba(255,255,255,0.15); border-color: #fff; color: #fff; }
        .hero-stats {
            display: flex;
            gap: 2.5rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }
        .hero-stat strong {
            display: block;
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
        }
        .hero-stat span {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.75);
            font-weight: 500;
        }
        .hero-mockup {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 1.5rem;
            backdrop-filter: blur(8px);
            max-width: 440px;
            margin-left: auto;
        }
        .mockup-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .mockup-dot {
            width: 10px; height: 10px; border-radius: 50%;
        }
        .mockup-card {
            background: rgba(255,255,255,0.95);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
        }
        .mockup-card-label { font-size: 0.65rem; color: var(--text-light); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .mockup-card-value { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-top: 0.2rem; }
        .mockup-card-sub { font-size: 0.75rem; color: var(--primary); font-weight: 500; }
        .mockup-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: 0.6rem; }
        .mockup-mini {
            background: rgba(255,255,255,0.95);
            border-radius: 10px;
            padding: 0.75rem;
        }
        .mockup-mini-icon { font-size: 1.2rem; margin-bottom: 0.25rem; }
        .mockup-mini-text { font-size: 0.72rem; color: var(--text-dark); font-weight: 600; }

        /* LOGOS STRIP */
        .logos-strip {
            background: #f8faff;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 1.25rem 0;
        }
        .logos-strip p { font-size: 0.8rem; color: var(--text-light); font-weight: 500; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
        .logo-item {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin: 0 1.5rem;
            opacity: 0.55;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        .logo-item i { font-size: 1.1rem; }

        /* SECTIONS */
        .section { padding: 5rem 0; }
        .section-alt { background: var(--gradient-light); }
        .section-label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary);
            margin-bottom: 0.75rem;
        }
        .section-title {
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .section-desc {
            font-size: 1rem;
            color: var(--text-gray);
            line-height: 1.7;
            max-width: 540px;
        }

        /* FEATURES */
        .feature-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }
        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--gradient-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: 1.25rem;
        }
        .feature-card h5 { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-dark); }
        .feature-card p { font-size: 0.87rem; color: var(--text-gray); line-height: 1.65; }

        /* MODULES */
        .module-item {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.75rem;
            margin-bottom: 1rem;
            transition: all 0.25s;
            cursor: default;
        }
        .module-item:hover { box-shadow: var(--shadow); border-color: var(--primary); }
        .module-item .module-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .module-item h6 { font-size: 0.95rem; font-weight: 700; margin-bottom: 0.3rem; }
        .module-item p { font-size: 0.82rem; color: var(--text-gray); margin: 0; line-height: 1.5; }
        .tag-list { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.6rem; }
        .tag {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            background: var(--gradient-light);
            color: var(--primary);
        }

        /* HOW IT WORKS */
        .step-card {
            text-align: center;
            padding: 2rem 1.5rem;
        }
        .step-number {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--gradient);
            color: #fff;
            font-size: 1.3rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 4px 16px rgba(102,126,234,0.4);
        }
        .step-card h5 { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; }
        .step-card p { font-size: 0.87rem; color: var(--text-gray); line-height: 1.65; }
        .step-connector {
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            opacity: 0.3;
            margin-top: 1.6rem;
        }

        /* PAYMENTS */
        .payment-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 0.5rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0.25rem;
            transition: all 0.2s;
        }
        .payment-badge:hover { border-color: var(--primary); color: var(--primary); box-shadow: 0 2px 12px rgba(102,126,234,0.15); }

        /* TESTIMONIALS */
        .testimonial-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
            height: 100%;
            position: relative;
        }
        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 1rem;
            left: 1.5rem;
            font-size: 4rem;
            color: var(--primary);
            opacity: 0.15;
            font-family: Georgia, serif;
            line-height: 1;
        }
        .testimonial-card p { font-size: 0.9rem; color: var(--text-gray); line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
        .testimonial-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .testimonial-name { font-size: 0.9rem; font-weight: 700; }
        .testimonial-role { font-size: 0.78rem; color: var(--text-light); }
        .stars { color: #f6a623; font-size: 0.8rem; margin-bottom: 0.75rem; }

        /* PRICING */
        .price-card {
            background: #fff;
            border: 2px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.3s;
        }
        .price-card.featured {
            border-color: var(--primary);
            background: var(--gradient);
            color: #fff;
            transform: scale(1.03);
            box-shadow: var(--shadow-hover);
        }
        .price-card:not(.featured):hover { border-color: var(--primary); box-shadow: var(--shadow); }
        .price-badge {
            display: inline-block;
            background: rgba(255,255,255,0.25);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            margin-bottom: 1rem;
        }
        .price-name { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem; }
        .price-desc { font-size: 0.82rem; opacity: 0.75; margin-bottom: 1.5rem; }
        .price-value { font-size: 2.8rem; font-weight: 800; line-height: 1; }
        .price-value sup { font-size: 1.2rem; font-weight: 600; vertical-align: top; margin-top: 0.5rem; display: inline-block; }
        .price-value sub { font-size: 0.85rem; font-weight: 400; opacity: 0.75; }
        .price-divider { border: none; border-top: 1px solid rgba(0,0,0,0.08); margin: 1.5rem 0; }
        .price-card.featured .price-divider { border-color: rgba(255,255,255,0.2); }
        .price-feature {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin-bottom: 0.75rem;
            font-size: 0.87rem;
        }
        .price-feature i { font-size: 0.8rem; margin-top: 0.2rem; flex-shrink: 0; }
        .price-feature.disabled { opacity: 0.4; }
        .btn-price-primary {
            display: block;
            width: 100%;
            background: #fff;
            color: var(--primary);
            font-weight: 700;
            border-radius: 50px;
            padding: 0.85rem;
            border: none;
            font-size: 0.95rem;
            margin-top: 1.5rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-price-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.15); color: var(--primary-dark); }
        .btn-price-outline {
            display: block;
            width: 100%;
            background: transparent;
            color: var(--primary);
            font-weight: 700;
            border-radius: 50px;
            padding: 0.85rem;
            border: 2px solid var(--primary);
            font-size: 0.95rem;
            margin-top: 1.5rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-price-outline:hover { background: var(--primary); color: #fff; }

        /* CTA FINAL */
        .cta-section {
            background: var(--gradient);
            padding: 5rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: -60%;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 800px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .cta-section h2 { font-size: clamp(1.8rem, 3vw, 2.6rem); font-weight: 800; color: #fff; margin-bottom: 1rem; }
        .cta-section p { font-size: 1.05rem; color: rgba(255,255,255,0.85); max-width: 520px; margin: 0 auto 2rem; }
        .btn-cta-white {
            background: #fff;
            color: var(--primary);
            font-weight: 700;
            border-radius: 50px;
            padding: 1rem 2.5rem;
            border: none;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.25s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .btn-cta-white:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,0.2); color: var(--primary-dark); }

        /* FOOTER */
        footer {
            background: #1a202c;
            color: rgba(255,255,255,0.7);
            padding: 3.5rem 0 1.5rem;
        }
        footer .brand { color: #fff; font-size: 1.3rem; font-weight: 800; margin-bottom: 0.5rem; }
        footer .brand span { background: var(--gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        footer p { font-size: 0.85rem; line-height: 1.7; }
        footer h6 { color: #fff; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; }
        footer a { color: rgba(255,255,255,0.6); text-decoration: none; font-size: 0.85rem; display: block; margin-bottom: 0.4rem; transition: color 0.2s; }
        footer a:hover { color: var(--primary); }
        footer .footer-divider { border-color: rgba(255,255,255,0.08); margin: 2rem 0 1.5rem; }
        footer .footer-bottom { font-size: 0.8rem; color: rgba(255,255,255,0.4); }

        /* MISC */
        .text-gradient {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .bg-primary-soft { background: var(--gradient-light); }
        @media (max-width: 768px) {
            .hero { padding: 4rem 0 3rem; }
            .hero-mockup { display: none; }
            .step-connector { display: none; }
            .price-card.featured { transform: none; }
        }
    </style>
</head>
<body>

<!-- ==================== NAVBAR ==================== -->
<nav class="navbar-custom" id="navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-calendar-check" style="color: var(--primary); font-size: 1.4rem;"></i>
            <span class="navbar-brand-text">AgendaPro</span>
        </div>
        <div class="d-none d-md-flex align-items-center gap-4">
            <a href="#funcionalidades" class="nav-link-custom">Funcionalidades</a>
            <a href="#modulos" class="nav-link-custom">Módulos</a>
            <a href="#pagamentos" class="nav-link-custom">Pagamentos</a>
            <a href="#planos" class="nav-link-custom">Planos</a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('home.login') }}" class="btn-nav-login text-decoration-none">Entrar</a>
            <a href="{{ route('home.registerAluno') }}" class="btn-nav-cta text-decoration-none">Começar grátis</a>
        </div>
    </div>
</nav>

<!-- ==================== HERO ==================== -->
<section class="hero">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="fa-solid fa-bolt me-1"></i> Plataforma completa de gestão
                </div>
                <h1>Gerencie seu negócio com <em style="font-style:normal;" class="text-white">inteligência</em></h1>
                <p class="lead">
                    Agendamentos, alunos, professores, pagamentos e finanças — tudo integrado numa única plataforma multi-tenant para escolas, estúdios e prestadores de serviço.
                </p>
                <div class="hero-btns">
                    <a href="{{ route('home.registerAluno') }}" class="btn-hero-primary text-decoration-none">
                        <i class="fa-solid fa-rocket me-2"></i> Começar gratuitamente
                    </a>
                    <a href="#funcionalidades" class="btn-hero-outline text-decoration-none">
                        <i class="fa-solid fa-play me-2"></i> Ver funcionalidades
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <strong>+500</strong>
                        <span>Empresas ativas</span>
                    </div>
                    <div class="hero-stat">
                        <strong>+10k</strong>
                        <span>Agendamentos/mês</span>
                    </div>
                    <div class="hero-stat">
                        <strong>99.9%</strong>
                        <span>Disponibilidade</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-mockup">
                    <div class="mockup-bar">
                        <div class="mockup-dot" style="background:#ff5f57;"></div>
                        <div class="mockup-dot" style="background:#ffbd2e;"></div>
                        <div class="mockup-dot" style="background:#28c840;"></div>
                        <span style="font-size:0.7rem; color:rgba(255,255,255,0.6); margin-left:0.5rem;">Dashboard — AgendaPro</span>
                    </div>
                    <div class="mockup-card">
                        <div class="mockup-card-label">Agendamentos hoje</div>
                        <div class="mockup-card-value">28 aulas confirmadas</div>
                        <div class="mockup-card-sub"><i class="fa-solid fa-arrow-trend-up me-1"></i>+12% vs semana passada</div>
                    </div>
                    <div class="mockup-grid">
                        <div class="mockup-mini">
                            <div class="mockup-mini-icon">👥</div>
                            <div class="mockup-mini-text">142 alunos ativos</div>
                        </div>
                        <div class="mockup-mini">
                            <div class="mockup-mini-icon">💰</div>
                            <div class="mockup-mini-text">R$ 18.4k faturado</div>
                        </div>
                        <div class="mockup-mini">
                            <div class="mockup-mini-icon">📅</div>
                            <div class="mockup-mini-text">6 modalidades</div>
                        </div>
                        <div class="mockup-mini">
                            <div class="mockup-mini-icon">⭐</div>
                            <div class="mockup-mini-text">4.9 avaliação média</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== LOGOS ==================== -->
<div class="logos-strip text-center">
    <div class="container">
        <p>Integrado com as principais ferramentas do mercado</p>
        <div>
            <span class="logo-item"><i class="fa-brands fa-stripe"></i> Stripe</span>
            <span class="logo-item"><i class="fa-brands fa-google"></i> Google Calendar</span>
            <span class="logo-item"><i class="fa-brands fa-whatsapp"></i> WhatsApp</span>
            <span class="logo-item"><i class="fa-brands fa-google"></i> Google OAuth</span>
            <span class="logo-item"><i class="fa-solid fa-barcode"></i> Asaas / Boleto</span>
        </div>
    </div>
</div>

<!-- ==================== FUNCIONALIDADES ==================== -->
<section class="section" id="funcionalidades">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-6">
                <div class="section-label">Funcionalidades</div>
                <h2 class="section-title">Tudo que você precisa para <span class="text-gradient">crescer</span></h2>
                <p class="section-desc mx-auto">Uma plataforma completa que elimina a necessidade de vários sistemas diferentes, centralizando toda a gestão do seu negócio.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h5>Agendamento Inteligente</h5>
                    <p>Sistema completo de agendamento com disponibilidade por horário, modalidades, turmas e confirmação automática. Alunos agendam online 24h.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
                    <h5>Gestão de Alunos e Professores</h5>
                    <p>Cadastro completo com histórico de aulas, planos ativos, pagamentos, avaliações e comunicação direta via chat integrado.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-credit-card"></i></div>
                    <h5>Cobranças Automáticas</h5>
                    <p>Aceite Cartão de Crédito, Pix, Boleto e Débito via Stripe e Asaas. Receba automaticamente e acompanhe o status de cada pagamento.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h5>Dashboard Financeiro</h5>
                    <p>Controle total de receitas, despesas, contas a receber e a pagar. Relatórios detalhados por período, categoria e método de pagamento.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-globe"></i></div>
                    <h5>Site Personalizado</h5>
                    <p>Cada empresa tem seu próprio site com domínio, template, portfólio de serviços, depoimentos e integração com blog e SEO.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-robot"></i></div>
                    <h5>Chatbot com IA</h5>
                    <p>Bot inteligente para atendimento automático de alunos, geração de conteúdo para blog e assistência via WhatsApp e chat interno.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== MODULOS ==================== -->
<section class="section section-alt" id="modulos">
    <div class="container">
        <div class="row align-items-start g-5">
            <div class="col-lg-5">
                <div class="section-label">Módulos do sistema</div>
                <h2 class="section-title">Uma plataforma <span class="text-gradient">completa</span> e modular</h2>
                <p class="section-desc">Cada módulo foi pensado para um fluxo específico do seu negócio, funcionando de forma integrada e coesa.</p>
                <div class="mt-4 p-3 rounded-3" style="background:#fff; border: 1px solid var(--border);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fa-solid fa-shield-halved text-success"></i>
                        <strong style="font-size:0.9rem;">Multi-tenant seguro</strong>
                    </div>
                    <p style="font-size:0.82rem; color:var(--text-gray); margin:0;">Cada empresa tem seu próprio ambiente isolado com domínio personalizado e permissões granulares por perfil de usuário.</p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="module-item d-flex align-items-start gap-3">
                    <div class="module-icon" style="background:#eff6ff; color:#3b82f6;"><i class="fa-solid fa-calendar-days"></i></div>
                    <div>
                        <h6>Agenda & Agendamento</h6>
                        <p>Calendário visual completo, configuração de disponibilidade por dia/horário, modalidades de aula, reservas e cancelamentos.</p>
                        <div class="tag-list">
                            <span class="tag">FullCalendar</span>
                            <span class="tag">Turmas</span>
                            <span class="tag">Modalidades</span>
                            <span class="tag">Disponibilidade</span>
                        </div>
                    </div>
                </div>
                <div class="module-item d-flex align-items-start gap-3">
                    <div class="module-icon" style="background:#f0fdf4; color:#16a34a;"><i class="fa-solid fa-money-bill-trend-up"></i></div>
                    <div>
                        <h6>Financeiro Completo</h6>
                        <p>Receitas, despesas, fluxo de caixa, contas recorrentes, planos de assinatura e relatórios exportáveis em Excel.</p>
                        <div class="tag-list">
                            <span class="tag">Receitas</span>
                            <span class="tag">Despesas</span>
                            <span class="tag">Planos</span>
                            <span class="tag">Excel Export</span>
                        </div>
                    </div>
                </div>
                <div class="module-item d-flex align-items-start gap-3">
                    <div class="module-icon" style="background:#fdf4ff; color:#9333ea;"><i class="fa-solid fa-comments"></i></div>
                    <div>
                        <h6>Comunicação em Tempo Real</h6>
                        <p>Chat entre alunos e professores via Socket.io, histórico de conversas e notificações automáticas por e-mail e SMS.</p>
                        <div class="tag-list">
                            <span class="tag">Socket.io</span>
                            <span class="tag">Chat</span>
                            <span class="tag">SMS</span>
                            <span class="tag">E-mail</span>
                        </div>
                    </div>
                </div>
                <div class="module-item d-flex align-items-start gap-3">
                    <div class="module-icon" style="background:#fff7ed; color:#ea580c;"><i class="fa-solid fa-newspaper"></i></div>
                    <div>
                        <h6>Blog & Conteúdo com IA</h6>
                        <p>Crie artigos, publique novidades e gere conteúdo automaticamente com IA integrada (DeepSeek / OpenAI).</p>
                        <div class="tag-list">
                            <span class="tag">Blog CMS</span>
                            <span class="tag">IA Generativa</span>
                            <span class="tag">SEO</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== COMO FUNCIONA ==================== -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-6">
                <div class="section-label">Como funciona</div>
                <h2 class="section-title">Comece em <span class="text-gradient">minutos</span></h2>
            </div>
        </div>
        <div class="row justify-content-center g-0">
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5>Crie sua conta</h5>
                    <p>Cadastre-se como empresa ou aluno. Configure seu perfil, modalidades e disponibilidade em poucos passos.</p>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-flex align-items-center">
                <div class="step-connector"></div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5>Configure seu negócio</h5>
                    <p>Defina serviços, preços, horários, professores e formas de pagamento aceitas pela plataforma.</p>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-flex align-items-center">
                <div class="step-connector"></div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5>Receba agendamentos</h5>
                    <p>Alunos agendam online, pagam automaticamente e você gerencia tudo pelo dashboard em tempo real.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PAGAMENTOS ==================== -->
<section class="section section-alt" id="pagamentos">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="section-label">Meios de pagamento</div>
                <h2 class="section-title">Aceite qualquer forma de <span class="text-gradient">pagamento</span></h2>
                <p class="section-desc">Integração nativa com os principais gateways do Brasil e do mundo. Cobranças automáticas, webhooks e reconciliação financeira inclusos.</p>
                <div class="mt-4">
                    <span class="payment-badge"><i class="fa-brands fa-stripe" style="color:#6772e5;"></i> Stripe</span>
                    <span class="payment-badge"><i class="fa-solid fa-barcode" style="color:#00b894;"></i> Asaas</span>
                    <span class="payment-badge"><i class="fa-solid fa-file-invoice" style="color:#e17055;"></i> Boleto</span>
                    <span class="payment-badge"><i class="fa-solid fa-qrcode" style="color:#0984e3;"></i> Pix</span>
                    <span class="payment-badge"><i class="fa-regular fa-credit-card" style="color:#a29bfe;"></i> Cartão de Crédito</span>
                    <span class="payment-badge"><i class="fa-solid fa-mobile-screen" style="color:#fd79a8;"></i> Débito</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="feature-card text-center p-3">
                            <i class="fa-solid fa-rotate me-2 text-primary" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            <h6 style="font-size:0.9rem; font-weight:700;">Cobranças Recorrentes</h6>
                            <p style="font-size:0.8rem; color:var(--text-gray);">Mensalidades automáticas para planos de assinatura</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card text-center p-3">
                            <i class="fa-solid fa-webhook text-primary" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            <h6 style="font-size:0.9rem; font-weight:700;">Webhooks Integrados</h6>
                            <p style="font-size:0.8rem; color:var(--text-gray);">Confirmação automática de pagamentos em tempo real</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card text-center p-3">
                            <i class="fa-solid fa-chart-pie text-primary" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            <h6 style="font-size:0.9rem; font-weight:700;">Relatórios Detalhados</h6>
                            <p style="font-size:0.8rem; color:var(--text-gray);">Exportação em Excel com filtros por período</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card text-center p-3">
                            <i class="fa-solid fa-shield-check text-primary" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            <h6 style="font-size:0.9rem; font-weight:700;">Pagamento Seguro</h6>
                            <p style="font-size:0.8rem; color:var(--text-gray);">Criptografia SSL e conformidade com PCI-DSS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== DEPOIMENTOS ==================== -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-6">
                <div class="section-label">Depoimentos</div>
                <h2 class="section-title">O que dizem nossos <span class="text-gradient">clientes</span></h2>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p>"Reduzimos em 80% o tempo gasto com agendamento manual. Os alunos adoraram poder marcar aulas pelo celular a qualquer hora."</p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="testimonial-avatar">AL</div>
                        <div>
                            <div class="testimonial-name">Ana Lima</div>
                            <div class="testimonial-role">Diretora — Estúdio Pilates Zen</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p>"O módulo financeiro transformou nossa visibilidade sobre o negócio. Hoje sei exatamente quanto vou receber no mês antes mesmo de começar."</p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="testimonial-avatar">CS</div>
                        <div>
                            <div class="testimonial-name">Carlos Santos</div>
                            <div class="testimonial-role">Proprietário — Academia FitLife</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p>"Ter meu próprio site com domínio e poder gerenciar tudo em um lugar só foi um diferencial enorme para minha escola de música."</p>
                    <div class="d-flex align-items-center gap-2">
                        <div class="testimonial-avatar">MO</div>
                        <div>
                            <div class="testimonial-name">Mariana Oliveira</div>
                            <div class="testimonial-role">Fundadora — Escola de Música Harmonia</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== PLANOS ==================== -->
<section class="section section-alt" id="planos">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-6">
                <div class="section-label">Planos e preços</div>
                <h2 class="section-title">Escolha o plano ideal para <span class="text-gradient">seu negócio</span></h2>
                <p class="section-desc mx-auto">Comece gratuitamente e escale conforme crescer. Sem taxa de adesão.</p>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="price-card">
                    <div class="price-name">Básico</div>
                    <div class="price-desc">Para quem está começando</div>
                    <div class="price-value"><sup>R$</sup>0<sub>/mês</sub></div>
                    <hr class="price-divider">
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Até 2 professores</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Até 30 alunos</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Agendamento online</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Site básico</div>
                    <div class="price-feature disabled"><i class="fa-solid fa-xmark text-danger"></i> Módulo financeiro</div>
                    <div class="price-feature disabled"><i class="fa-solid fa-xmark text-danger"></i> Chatbot com IA</div>
                    <div class="price-feature disabled"><i class="fa-solid fa-xmark text-danger"></i> Domínio personalizado</div>
                    <a href="{{ route('home.registerAluno') }}" class="btn-price-outline">Criar conta grátis</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="price-card featured">
                    <div class="price-badge">Mais popular</div>
                    <div class="price-name">Profissional</div>
                    <div class="price-desc">Para negócios em crescimento</div>
                    <div class="price-value"><sup>R$</sup>97<sub>/mês</sub></div>
                    <hr class="price-divider">
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Professores ilimitados</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Alunos ilimitados</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Agendamento online</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Site + domínio próprio</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Módulo financeiro completo</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Pagamentos automáticos</div>
                    <div class="price-feature disabled" style="color:rgba(255,255,255,0.4);"><i class="fa-solid fa-xmark"></i> Chatbot com IA</div>
                    <a href="{{ route('home.registerAluno') }}" class="btn-price-primary">Assinar agora</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="price-card">
                    <div class="price-name">Enterprise</div>
                    <div class="price-desc">Para grandes operações</div>
                    <div class="price-value"><sup>R$</sup>247<sub>/mês</sub></div>
                    <hr class="price-divider">
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Tudo do Profissional</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Chatbot com IA</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Geração de conteúdo IA</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Multi-domínios</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> API & Webhooks</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Google Calendar Sync</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Suporte prioritário</div>
                    <a href="{{ route('home.registerAluno') }}" class="btn-price-outline">Contratar</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA FINAL ==================== -->
<section class="cta-section">
    <div class="container position-relative">
        <h2>Pronto para transformar sua gestão?</h2>
        <p>Junte-se a centenas de negócios que já usam o AgendaPro para crescer com mais organização e menos esforço.</p>
        <a href="{{ route('home.registerAluno') }}" class="btn-cta-white">
            <i class="fa-solid fa-rocket me-2"></i> Começar agora — é grátis
        </a>
        <div class="mt-3" style="color:rgba(255,255,255,0.65); font-size:0.82rem;">
            Sem cartão de crédito &bull; Configuração em 5 minutos &bull; Cancele quando quiser
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="brand"><i class="fa-solid fa-calendar-check me-2"></i><span>AgendaPro</span></div>
                <p class="mt-2">Plataforma completa de gestão para escolas, estúdios e prestadores de serviço. Multi-tenant, seguro e escalável.</p>
            </div>
            <div class="col-lg-2 col-md-4">
                <h6>Produto</h6>
                <a href="#funcionalidades">Funcionalidades</a>
                <a href="#modulos">Módulos</a>
                <a href="#planos">Planos</a>
                <a href="#pagamentos">Pagamentos</a>
            </div>
            <div class="col-lg-2 col-md-4">
                <h6>Plataforma</h6>
                <a href="{{ route('home.login') }}">Entrar</a>
                <a href="{{ route('home.registerAluno') }}">Cadastrar aluno</a>
                <a href="{{ route('home.registerProf') }}">Cadastrar professor</a>
                <a href="{{ route('home.index') }}">Ver empresas</a>
            </div>
            <div class="col-lg-2 col-md-4">
                <h6>Suporte</h6>
                <a href="#">Documentação</a>
                <a href="#">Central de ajuda</a>
                <a href="#">Status da plataforma</a>
                <a href="#">Contato</a>
            </div>
            <div class="col-lg-2">
                <h6>Legal</h6>
                <a href="#">Termos de uso</a>
                <a href="#">Privacidade</a>
                <a href="#">LGPD</a>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="footer-bottom">© {{ date('Y') }} AgendaPro. Todos os direitos reservados.</div>
            <div class="d-flex gap-3">
                <a href="#" style="color:rgba(255,255,255,0.4); font-size:1.1rem;"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" style="color:rgba(255,255,255,0.4); font-size:1.1rem;"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="#" style="color:rgba(255,255,255,0.4); font-size:1.1rem;"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 20);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>
</body>
</html>
