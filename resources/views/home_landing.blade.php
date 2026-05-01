<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PilatesGestão — Sistema completo para estúdios de Pilates</title>
    <meta name="description" content="O sistema feito para estúdios de Pilates: agenda, alunos, mensalidades automáticas, financeiro e site próprio. Pare de gerenciar no caderno e no WhatsApp.">
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
            --whatsapp: #25d366;
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
            max-width: 540px;
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
        .hero-trust {
            display: flex;
            gap: 1.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
            color: rgba(255,255,255,0.85);
            font-size: 0.85rem;
        }
        .hero-trust span { display: flex; align-items: center; gap: 0.4rem; }
        .hero-trust i { color: #b9f6ca; }
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

        /* DIFERENCIAIS (substitui depoimentos) */
        .diff-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .diff-card .diff-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: var(--gradient-light);
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); font-size: 1.3rem;
        }
        .diff-card h5 { font-size: 1.05rem; font-weight: 700; }
        .diff-card p { font-size: 0.9rem; color: var(--text-gray); line-height: 1.65; }

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
        .btn-cta-wpp {
            background: var(--whatsapp);
            color: #fff;
            font-weight: 700;
            border-radius: 50px;
            padding: 1rem 2rem;
            border: none;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            margin-left: 0.75rem;
            transition: all 0.25s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .btn-cta-wpp:hover { transform: translateY(-3px); color: #fff; opacity: 0.95; }

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

        /* WHATSAPP FLOATING BUTTON */
        .wpp-float {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--whatsapp);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            text-decoration: none;
            box-shadow: 0 6px 24px rgba(37, 211, 102, 0.5);
            z-index: 9999;
            transition: all 0.25s;
            animation: wpp-pulse 2.4s infinite;
        }
        .wpp-float:hover { transform: scale(1.08); color: #fff; }
        .wpp-float-label {
            position: absolute;
            right: 70px;
            background: #fff;
            color: var(--text-dark);
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.5rem 0.9rem;
            border-radius: 50px;
            white-space: nowrap;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.25s;
            pointer-events: none;
        }
        .wpp-float:hover .wpp-float-label { opacity: 1; transform: translateX(0); }
        @keyframes wpp-pulse {
            0% { box-shadow: 0 6px 24px rgba(37,211,102,0.5), 0 0 0 0 rgba(37,211,102,0.5); }
            70% { box-shadow: 0 6px 24px rgba(37,211,102,0.5), 0 0 0 18px rgba(37,211,102,0); }
            100% { box-shadow: 0 6px 24px rgba(37,211,102,0.5), 0 0 0 0 rgba(37,211,102,0); }
        }

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
            .btn-cta-wpp { margin-left: 0; margin-top: 0.75rem; }
            .wpp-float { width: 54px; height: 54px; font-size: 1.6rem; bottom: 18px; right: 18px; }
        }
    </style>
</head>
<body>

{{-- 
    ============================================================
    CONFIGURE AQUI: número do WhatsApp e mensagens iniciais
    Formato: 55 (país) + DDD + número, sem espaços, sem traços.
    Ex.: 5521987654321
    ============================================================
--}}
@php
    $whatsapp_numero = '5521999999999'; // <-- TROQUE pelo seu número
    $whatsapp_msg_demo = rawurlencode('Olá! Tenho um estúdio de Pilates e queria agendar uma demonstração do PilatesGestão.');
    $whatsapp_msg_geral = rawurlencode('Olá! Vim pelo site do PilatesGestão e tenho uma dúvida.');
@endphp

<!-- ==================== NAVBAR ==================== -->
<nav class="navbar-custom" id="navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-calendar-check" style="color: var(--primary); font-size: 1.4rem;"></i>
            <span class="navbar-brand-text">PilatesGestão</span>
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
                    <i class="fa-solid fa-bolt me-1"></i> Feito para estúdios de Pilates
                </div>
                <h1>Pare de gerenciar seu estúdio no <em style="font-style:normal;" class="text-white">caderno</em> e no <em style="font-style:normal;" class="text-white">WhatsApp</em></h1>
                <p class="lead">
                    Agenda de aparelho e solo, mensalidades automáticas no Pix e cartão, controle de alunos e instrutores — tudo num único sistema feito sob medida pra estúdios de Pilates.
                </p>
                <div class="hero-btns">
                    <a href="{{ route('home.registerAluno') }}" class="btn-hero-primary text-decoration-none">
                        <i class="fa-solid fa-rocket me-2"></i> Testar 14 dias grátis
                    </a>
                    <a href="https://wa.me/{{ $whatsapp_numero }}?text={{ $whatsapp_msg_demo }}" target="_blank" rel="noopener" class="btn-hero-outline text-decoration-none">
                        <i class="fa-brands fa-whatsapp me-2"></i> Agendar demonstração
                    </a>
                </div>
                <div class="hero-trust">
                    <span><i class="fa-solid fa-check"></i> Sem cartão de crédito</span>
                    <span><i class="fa-solid fa-check"></i> Configura em 15 min</span>
                    <span><i class="fa-solid fa-check"></i> Cancele quando quiser</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-mockup">
                    <div class="mockup-bar">
                        <div class="mockup-dot" style="background:#ff5f57;"></div>
                        <div class="mockup-dot" style="background:#ffbd2e;"></div>
                        <div class="mockup-dot" style="background:#28c840;"></div>
                        <span style="font-size:0.7rem; color:rgba(255,255,255,0.6); margin-left:0.5rem;">Painel — PilatesGestão</span>
                    </div>
                    <div class="mockup-card">
                        <div class="mockup-card-label">Aulas hoje</div>
                        <div class="mockup-card-value">12 aulas confirmadas</div>
                        <div class="mockup-card-sub"><i class="fa-solid fa-circle-check me-1"></i>3 horários ainda livres</div>
                    </div>
                    <div class="mockup-grid">
                        <div class="mockup-mini">
                            <div class="mockup-mini-icon">🧘‍♀️</div>
                            <div class="mockup-mini-text">Aparelho • 8</div>
                        </div>
                        <div class="mockup-mini">
                            <div class="mockup-mini-icon">🤸</div>
                            <div class="mockup-mini-text">Solo • 4</div>
                        </div>
                        <div class="mockup-mini">
                            <div class="mockup-mini-icon">💰</div>
                            <div class="mockup-mini-text">Pix recebido hoje</div>
                        </div>
                        <div class="mockup-mini">
                            <div class="mockup-mini-icon">📲</div>
                            <div class="mockup-mini-text">2 alunos novos</div>
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
        <p>Integrado com as ferramentas que seu estúdio já usa</p>
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
                <h2 class="section-title">Tudo que seu estúdio precisa em <span class="text-gradient">um lugar só</span></h2>
                <p class="section-desc mx-auto">Chega de pular entre planilha, agenda física, WhatsApp e maquininha. Centralize a gestão do seu estúdio numa única plataforma.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h5>Agenda de Aparelho e Solo</h5>
                    <p>Configure horários, vagas por aparelho e modalidades. Aluno agenda online 24h sem precisar te chamar no WhatsApp.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
                    <h5>Ficha do Aluno Completa</h5>
                    <p>Histórico de aulas, plano ativo, pagamentos, observações da avaliação e contato direto. Tudo num clique.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-credit-card"></i></div>
                    <h5>Mensalidade Automática</h5>
                    <p>Pix, cartão, boleto recorrente. O sistema cobra, confirma e avisa o aluno em atraso — você não precisa correr atrás.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h5>Financeiro do Estúdio</h5>
                    <p>Receitas, despesas, contas a receber e fluxo de caixa. Saiba antes do dia 1 quanto vai entrar no mês.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-globe"></i></div>
                    <h5>Site Próprio do Estúdio</h5>
                    <p>Página com seu domínio, fotos, modalidades, depoimentos e botão de agendamento direto. Pronta em minutos.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-robot"></i></div>
                    <h5>Atendimento com IA</h5>
                    <p>Chatbot responde dúvidas comuns dos alunos no WhatsApp e no site, libera tempo da recepção e nunca dorme.</p>
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
                <p class="section-desc">Cada módulo foi pensado para um fluxo específico do estúdio de Pilates, funcionando de forma integrada e coesa.</p>
                <div class="mt-4 p-3 rounded-3" style="background:#fff; border: 1px solid var(--border);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fa-solid fa-shield-halved text-success"></i>
                        <strong style="font-size:0.9rem;">Cada estúdio com seu próprio espaço</strong>
                    </div>
                    <p style="font-size:0.82rem; color:var(--text-gray); margin:0;">Arquitetura multi-tenant: seus dados isolados, domínio próprio e permissões diferentes para dono, recepção e instrutor.</p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="module-item d-flex align-items-start gap-3">
                    <div class="module-icon" style="background:#eff6ff; color:#3b82f6;"><i class="fa-solid fa-calendar-days"></i></div>
                    <div>
                        <h6>Agenda & Agendamento</h6>
                        <p>Calendário visual, configuração por aparelho e horário, modalidades, reservas e lista de espera.</p>
                        <div class="tag-list">
                            <span class="tag">Aparelho</span>
                            <span class="tag">Solo</span>
                            <span class="tag">Turmas</span>
                            <span class="tag">Lista de espera</span>
                        </div>
                    </div>
                </div>
                <div class="module-item d-flex align-items-start gap-3">
                    <div class="module-icon" style="background:#f0fdf4; color:#16a34a;"><i class="fa-solid fa-money-bill-trend-up"></i></div>
                    <div>
                        <h6>Financeiro Completo</h6>
                        <p>Receitas, despesas, fluxo de caixa, contas recorrentes, planos de assinatura e relatórios em Excel.</p>
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
                        <h6>Comunicação com Alunos</h6>
                        <p>Chat entre alunos e instrutores, lembretes automáticos de aula e cobrança por e-mail e SMS.</p>
                        <div class="tag-list">
                            <span class="tag">Chat</span>
                            <span class="tag">Lembretes</span>
                            <span class="tag">SMS</span>
                            <span class="tag">E-mail</span>
                        </div>
                    </div>
                </div>
                <div class="module-item d-flex align-items-start gap-3">
                    <div class="module-icon" style="background:#fff7ed; color:#ea580c;"><i class="fa-solid fa-newspaper"></i></div>
                    <div>
                        <h6>Blog & Conteúdo com IA</h6>
                        <p>Publique artigos sobre Pilates, dicas e novidades. A IA ajuda a gerar conteúdo otimizado pra Google.</p>
                        <div class="tag-list">
                            <span class="tag">Blog</span>
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
                <h2 class="section-title">Seu estúdio organizado em <span class="text-gradient">15 minutos</span></h2>
            </div>
        </div>
        <div class="row justify-content-center g-0">
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5>Crie sua conta</h5>
                    <p>Cadastre seu estúdio, configure modalidades (aparelho, solo, kids) e horários disponíveis.</p>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-flex align-items-center">
                <div class="step-connector"></div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5>Importe seus alunos</h5>
                    <p>Suba sua planilha ou cadastre um a um. Nossa equipe ajuda na migração se você quiser.</p>
                </div>
            </div>
            <div class="col-md-1 d-none d-md-flex align-items-center">
                <div class="step-connector"></div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5>Comece a receber</h5>
                    <p>Alunos agendam pelo celular, mensalidades caem automático no Pix e você acompanha tudo num painel.</p>
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
                <h2 class="section-title">Receba do jeito que <span class="text-gradient">o aluno preferir</span></h2>
                <p class="section-desc">Integração nativa com os principais gateways do Brasil. Cobrança automática, baixa no recebimento e relatório financeiro consolidado.</p>
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
                            <h6 style="font-size:0.9rem; font-weight:700;">Mensalidade Recorrente</h6>
                            <p style="font-size:0.8rem; color:var(--text-gray);">Cobrança automática todo mês, sem você precisar lembrar.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card text-center p-3">
                            <i class="fa-solid fa-bell text-primary" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            <h6 style="font-size:0.9rem; font-weight:700;">Aviso de Inadimplência</h6>
                            <p style="font-size:0.8rem; color:var(--text-gray);">Aluno em atraso recebe lembrete automático por WhatsApp.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card text-center p-3">
                            <i class="fa-solid fa-chart-pie text-primary" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            <h6 style="font-size:0.9rem; font-weight:700;">Relatórios Detalhados</h6>
                            <p style="font-size:0.8rem; color:var(--text-gray);">Quanto entrou, quanto falta, quem deve. Exporta em Excel.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-card text-center p-3">
                            <i class="fa-solid fa-shield-halved text-primary" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                            <h6 style="font-size:0.9rem; font-weight:700;">Pagamento Seguro</h6>
                            <p style="font-size:0.8rem; color:var(--text-gray);">Criptografia SSL e gateways com certificação PCI-DSS.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== POR QUE ESCOLHER (substitui depoimentos fakes) ==================== -->
<section class="section">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-7">
                <div class="section-label">Por que escolher o PilatesGestão</div>
                <h2 class="section-title">Construído <span class="text-gradient">para Pilates</span>, não adaptado de outro segmento</h2>
                <p class="section-desc mx-auto">Sistemas genéricos de academia ou clínica não entendem o que é uma turma de aparelho com 5 vagas, uma reposição ou um plano com frequência variável. Nós entendemos.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="diff-card">
                    <div class="diff-icon"><i class="fa-solid fa-dumbbell"></i></div>
                    <h5>Pensado pra rotina do estúdio</h5>
                    <p>Vagas por aparelho, agendamento por nível, reposição de aulas, frequência mensal — funcionalidades que você não encontra em sistema genérico.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="diff-card">
                    <div class="diff-icon"><i class="fa-solid fa-headset"></i></div>
                    <h5>Suporte humano no WhatsApp</h5>
                    <p>Falar com gente de verdade quando precisa. Sem ticket que demora 3 dias, sem chatbot empurrando artigo de ajuda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="diff-card">
                    <div class="diff-icon"><i class="fa-solid fa-truck-arrow-right"></i></div>
                    <h5>Migração assistida grátis</h5>
                    <p>Já usa planilha ou outro sistema? Nossa equipe importa seus alunos, planos e histórico pra você não começar do zero.</p>
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
                <h2 class="section-title">Escolha o plano ideal pro <span class="text-gradient">seu estúdio</span></h2>
                <p class="section-desc mx-auto">Comece testando 14 dias grátis. Sem taxa de adesão, sem fidelidade.</p>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="price-card">
                    <div class="price-name">Básico</div>
                    <div class="price-desc">Pra estúdios começando</div>
                    <div class="price-value"><sup>R$</sup>0<sub>/mês</sub></div>
                    <hr class="price-divider">
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Até 2 instrutores</div>
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
                    <div class="price-badge">Mais escolhido</div>
                    <div class="price-name">Profissional</div>
                    <div class="price-desc">Pra estúdios em crescimento</div>
                    <div class="price-value"><sup>R$</sup>97<sub>/mês</sub></div>
                    <hr class="price-divider">
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Instrutores ilimitados</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Alunos ilimitados</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Agendamento online</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Site + domínio próprio</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Módulo financeiro completo</div>
                    <div class="price-feature"><i class="fa-solid fa-check"></i> Mensalidade automática</div>
                    <div class="price-feature disabled" style="color:rgba(255,255,255,0.4);"><i class="fa-solid fa-xmark"></i> Chatbot com IA</div>
                    <a href="{{ route('home.registerAluno') }}" class="btn-price-primary">Testar 14 dias grátis</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="price-card">
                    <div class="price-name">Enterprise</div>
                    <div class="price-desc">Pra redes e estúdios grandes</div>
                    <div class="price-value"><sup>R$</sup>247<sub>/mês</sub></div>
                    <hr class="price-divider">
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Tudo do Profissional</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Chatbot com IA</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Geração de conteúdo IA</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Multi-unidades</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> API & Webhooks</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Sincronização Google Calendar</div>
                    <div class="price-feature"><i class="fa-solid fa-check text-success"></i> Suporte prioritário</div>
                    <a href="https://wa.me/{{ $whatsapp_numero }}?text={{ rawurlencode('Olá! Tenho interesse no plano Enterprise do PilatesGestão.') }}" target="_blank" rel="noopener" class="btn-price-outline">Falar com vendas</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CTA FINAL ==================== -->
<section class="cta-section">
    <div class="container position-relative">
        <h2>Pronto pra deixar a planilha pra trás?</h2>
        <p>Teste 14 dias grátis ou agende uma demonstração de 20 minutos. Sem cartão de crédito, sem compromisso.</p>
        <div class="d-inline-flex flex-wrap justify-content-center align-items-center">
            <a href="{{ route('home.registerAluno') }}" class="btn-cta-white">
                <i class="fa-solid fa-rocket me-2"></i> Começar grátis
            </a>
            <a href="https://wa.me/{{ $whatsapp_numero }}?text={{ $whatsapp_msg_demo }}" target="_blank" rel="noopener" class="btn-cta-wpp">
                <i class="fa-brands fa-whatsapp me-2"></i> Agendar demonstração
            </a>
        </div>
        <div class="mt-3" style="color:rgba(255,255,255,0.65); font-size:0.82rem;">
            Sem cartão de crédito &bull; Configura em 15 minutos &bull; Cancele quando quiser
        </div>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer>
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="brand"><i class="fa-solid fa-calendar-check me-2"></i><span>PilatesGestão</span></div>
                <p class="mt-2">O sistema feito sob medida pra estúdios de Pilates: agenda, alunos, mensalidades e financeiro num lugar só.</p>
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
                <a href="{{ route('home.registerProf') }}">Cadastrar instrutor</a>
                <a href="{{ route('home.index') }}">Ver estúdios</a>
            </div>
            <div class="col-lg-2 col-md-4">
                <h6>Suporte</h6>
                <a href="https://wa.me/{{ $whatsapp_numero }}?text={{ $whatsapp_msg_geral }}" target="_blank" rel="noopener">WhatsApp</a>
                <a href="mailto:contato@pilatesgestao.com.br">contato@pilatesgestao.com.br</a>
                <a href="#">Central de ajuda</a>
                <a href="#">Status</a>
            </div>
            <div class="col-lg-2">
                <h6>Legal</h6>
                <a href="{{ url('/termos') }}">Termos de uso</a>
                <a href="{{ url('/privacidade') }}">Política de Privacidade</a>
                <a href="{{ url('/lgpd') }}">LGPD</a>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="footer-bottom">© {{ date('Y') }} PilatesGestão. Todos os direitos reservados.</div>
            <div class="d-flex gap-3">
                <a href="#" style="color:rgba(255,255,255,0.4); font-size:1.1rem;"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://wa.me/{{ $whatsapp_numero }}" target="_blank" rel="noopener" style="color:rgba(255,255,255,0.4); font-size:1.1rem;"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="#" style="color:rgba(255,255,255,0.4); font-size:1.1rem;"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>
    </div>
</footer>

<!-- ==================== WHATSAPP FLOATING BUTTON ==================== -->
<a href="https://wa.me/{{ $whatsapp_numero }}?text={{ $whatsapp_msg_geral }}"
   target="_blank"
   rel="noopener"
   class="wpp-float"
   aria-label="Falar no WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
    <span class="wpp-float-label">Tire suas dúvidas no WhatsApp</span>
</a>

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