<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $site->titulo ?? 'Fight Club Academy' }} - Transforme-se Através do Boxe</title>
    <meta name="description" content="{{ $site->descricao ?? 'Transforme sua vida através do boxe. Academia completa com instrutores certificados.' }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            @if($site->cores)
                --primary-color: {{ $site->cores['primaria'] ?? '#ff4444' }};
                --secondary-color: {{ $site->cores['secundaria'] ?? '#ff6b35' }};
                --accent-color: {{ $site->cores['acento'] ?? '#ffd700' }};
            @else
                --primary-color: #ff4444;
                --secondary-color: #ff6b35;
                --accent-color: #ffd700;
            @endif
            --dark-bg: #0f0f0f;
            --darker-bg: #050505;
            --glass-bg: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark-bg);
            color: #fff;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--darker-bg);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }

        /* Loading Screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 50%, var(--primary-color) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .boxing-gloves {
            position: relative;
            animation: boxingPunch 2s ease-in-out infinite;
        }

        @keyframes boxingPunch {
            0%, 100% { transform: translateX(0) rotate(0deg); }
            25% { transform: translateX(-10px) rotate(-5deg); }
            75% { transform: translateX(10px) rotate(5deg); }
        }

        .loading-text {
            font-family: 'Bebas Neue', cursive;
            font-size: 1.5rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 2rem;
            opacity: 0;
            animation: fadeInText 2s ease-in-out 0.5s forwards;
        }

        @keyframes fadeInText {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Enhanced Navigation */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 68, 68, 0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .header-scrolled {
            background: rgba(5, 5, 5, 0.98);
            box-shadow: 0 4px 30px rgba(255, 68, 68, 0.1);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
        }

        .logo {
            font-family: 'Bebas Neue', cursive;
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 3px;
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .logo:hover::after {
            transform: scaleX(1);
        }

        .nav-menu {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            align-items: center;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-menu a::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-menu a:hover {
            color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .nav-menu a:hover::before {
            width: 100%;
        }

        /* Enhanced Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 30%, rgba(255, 68, 68, 0.1) 100%);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,68,68,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,107,53,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(255,215,0,0.1)"/></svg>') repeat;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-20px) translateX(-10px); }
            100% { transform: translateY(0px) translateX(0px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-family: 'Bebas Neue', cursive;
            font-size: clamp(3rem, 8vw, 7rem);
            line-height: 0.9;
            margin-bottom: 2rem;
            background: linear-gradient(45deg, #fff, var(--secondary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 4px;
            text-shadow: 0 0 30px rgba(255, 68, 68, 0.3);
        }

        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            margin-bottom: 3rem;
            opacity: 0.9;
            font-weight: 400;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            padding: 1.2rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 30px rgba(255, 68, 68, 0.4);
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid var(--secondary-color);
            padding: 1.2rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: var(--secondary-color);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .btn-secondary:hover::before {
            width: 100%;
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            color: white;
            box-shadow: 0 15px 30px rgba(255, 107, 53, 0.4);
        }

        /* Enhanced Carousel */
        .hero-carousel {
            position: relative;
            width: 100%;
            max-width: 600px;
            height: 500px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .carousel-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 68, 68, 0.3), rgba(255, 107, 53, 0.3));
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .carousel-slide:hover .carousel-overlay {
            opacity: 1;
        }

        /* Enhanced Services Section */
        .services-section {
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--darker-bg) 100%);
            position: relative;
        }

        .service-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-20px) scale(1.02);
            border-color: var(--secondary-color);
            box-shadow: 0 30px 60px rgba(255, 68, 68, 0.2);
        }

        .service-card:hover::before {
            opacity: 0.1;
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Enhanced About Section */
        .about-section {
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 50%, rgba(255, 68, 68, 0.05) 100%);
            position: relative;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateX(10px);
            border-color: var(--secondary-color);
            box-shadow: 0 10px 30px rgba(255, 68, 68, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            flex-shrink: 0;
        }

        /* Enhanced Testimonials */
        .testimonial-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.4s ease;
            position: relative;
            height: 100%;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 4rem;
            color: var(--secondary-color);
            opacity: 0.3;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            border-color: var(--secondary-color);
            box-shadow: 0 20px 40px rgba(255, 68, 68, 0.1);
        }

        /* WhatsApp Button Enhancement */
        .whatsapp-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, #25d366, #128c7e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            animation: pulse-whatsapp 2s infinite;
        }

        @keyframes pulse-whatsapp {
            0% { box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4); }
            50% { box-shadow: 0 8px 35px rgba(37, 211, 102, 0.7); transform: scale(1.05); }
            100% { box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4); }
        }

        .whatsapp-btn:hover {
            transform: scale(1.1);
            animation: none;
        }

        /* Section Headers */
        .section-header h2 {
            font-family: 'Bebas Neue', cursive;
            font-size: clamp(2.5rem, 5vw, 4rem);
            background: linear-gradient(45deg, #fff, var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 1rem;
        }

        .section-divider {
            width: 100px;
            height: 4px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            margin: 0 auto 2rem;
            border-radius: 2px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .nav-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(5, 5, 5, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 2rem;
                transform: translateY(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .nav-menu.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }

            .hamburger {
                display: block;
                font-size: 1.5rem;
                color: white;
                cursor: pointer;
                transition: transform 0.3s ease;
            }

            .hamburger.active {
                transform: rotate(90deg);
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
                max-width: 300px;
                text-align: center;
            }

            .hero-carousel {
                height: 350px;
            }
        }

        @media (max-width: 640px) {
            .nav-container {
                padding: 1rem;
            }

            .logo {
                font-size: 2rem;
            }

            .hero-carousel {
                height: 300px;
            }

            .service-card {
                padding: 1.5rem;
            }

            .whatsapp-btn {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        /* Scroll Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(50px);
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-50px);
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(50px);
        }

        .scale-up {
            opacity: 0;
            transform: scale(0.8);
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loading">
        <div class="boxing-gloves">
            <i class="fas fa-fist-raised text-6xl text-white"></i>
        </div>
        <p class="loading-text">Preparando o treino...</p>
    </div>

    <!-- WhatsApp Button -->
    @if($site->whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site->whatsapp) }}?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20os%20treinos!" 
       class="whatsapp-btn" onclick="registrarCliqueWhatsapp()">
        <i class="fab fa-whatsapp"></i>
    </a>
    @endif

    <!-- Navigation -->
    <header id="header">
        <div class="nav-container">
            <div class="logo">{{ $site->titulo ?? 'Fight Club Academy' }}</div>
            <div class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </div>
            <nav>
                <ul class="nav-menu" id="nav-menu">
                    <li><a href="#home">Início</a></li>
                    <li><a href="#services">Serviços</a></li>
                    <li><a href="#about">Sobre</a></li>
                    @if($site->depoimentos->count() > 0)
                    <li><a href="#testimonials">Depoimentos</a></li>
                    @endif
                    <li><a href="#contact">Contato</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container mx-auto px-6 relative z-10 max-w-7xl">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="hero-content">
                    <h1 class="hero-title fade-in-up">
                        {{ $site->titulo ?? 'Desperte o Lutador que Existe em Você' }}
                    </h1>
                    <p class="hero-subtitle fade-in-up">
                        {{ $site->descricao ?? 'Transforme sua vida através do boxe. Treine com os melhores, desenvolva disciplina, força e confiança. Sua jornada de transformação começa agora!' }}
                    </p>
                    <div class="hero-buttons fade-in-up">
                        <a href="#services" class="btn-primary">
                            <i class="fas fa-fist-raised mr-2"></i>
                            Comece Sua Transformação
                        </a>
                        <a href="#about" class="btn-secondary">
                            <i class="fas fa-play mr-2"></i>
                            Ver Mais
                        </a>
                    </div>
                </div>
                
                <div class="hero-carousel fade-in-right">
                    @if($site->capa)
                    <div class="carousel-slide active">
                        <img src="{{ Storage::url($site->capa) }}" alt="{{ $site->titulo }}">
                        <div class="carousel-overlay">
                            <i class="fas fa-boxing-glove text-white text-4xl"></i>
                        </div>
                    </div>
                    @endif
                    
                    @if($site->sobre_imagem)
                    <div class="carousel-slide {{ !$site->capa ? 'active' : '' }}">
                        <img src="{{ Storage::url($site->sobre_imagem) }}" alt="Sobre {{ $site->titulo }}">
                        <div class="carousel-overlay">
                            <i class="fas fa-dumbbell text-white text-4xl"></i>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Imagens padrão se não houver imagens cadastradas -->
                    @if(!$site->capa && !$site->sobre_imagem)
                    <div class="carousel-slide active">
                        <img src="https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Treinamento de Boxe">
                        <div class="carousel-overlay">
                            <i class="fas fa-boxing-glove text-white text-4xl"></i>
                        </div>
                    </div>
                    <div class="carousel-slide">
                        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Academia de Boxe">
                        <div class="carousel-overlay">
                            <i class="fas fa-dumbbell text-white text-4xl"></i>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    @if($site->siteServicos->count() > 0 || $site->servicos->count() > 0)
    <section id="services" class="services-section py-20">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="section-header text-center mb-16">
                <h2 class="fade-in-up">Nossos Serviços</h2>
                <div class="section-divider fade-in-up"></div>
                <p class="text-xl opacity-80 fade-in-up">
                    Oferecemos uma experiência completa para todos os níveis - do iniciante ao profissional.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $servicos = $site->siteServicos->count() > 0 ? $site->siteServicos : $site->servicos;
                @endphp
                
                @foreach($servicos as $index => $servico)
                <div class="service-card fade-in-up" style="animation-delay: {{ $index * 0.2 }}s;">
                    <div class="service-icon">
                        <i class="{{ $servico->icone ?? 'fas fa-boxing-glove' }}"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">{{ $servico->nome ?? $servico->titulo }}</h3>
                    <p class="text-gray-300 mb-6">{{ $servico->descricao }}</p>
                    <div class="flex justify-between items-center">
                        @if(isset($servico->preco) && $servico->preco)
                        <span class="text-3xl font-bold text-orange-400">R$ {{ number_format($servico->preco, 2, ',', '.') }}/mês</span>
                        @endif
                        @if($site->whatsapp)
                        <button class="btn-primary text-sm px-4 py-2" onclick="openWhatsApp('{{ $servico->nome ?? $servico->titulo }}')">
                            Matricular
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- About Section -->
    @if($site->sobre_titulo || $site->sobre_descricao)
    <section id="about" class="about-section py-20">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in-left">
                    <h2 class="text-4xl lg:text-5xl font-bold mb-6" style="font-family: 'Bebas Neue', cursive; background: linear-gradient(45deg, #fff, var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        {{ $site->sobre_titulo ?? 'Por que nos escolher?' }}
                    </h2>
                    <p class="text-xl text-gray-300 mb-8">
                        {{ $site->sobre_descricao ?? 'Com mais de 10 anos de experiência, somos a academia líder na região, formando campeões e transformando vidas através da disciplina e dedicação.' }}
                    </p>

                    <div class="space-y-4">
                        @if($site->sobre_itens && is_array($site->sobre_itens))
                            @foreach($site->sobre_itens as $item)
                            <div class="feature-item flex items-center">
                                <div class="feature-icon">
                                    <i class="{{ $item['icone'] ?? 'fas fa-trophy' }} text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold mb-2">{{ $item['titulo'] }}</h4>
                                    <p class="text-gray-300">{{ $item['descricao'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Itens padrão se não houver sobre_itens cadastrados -->
                            <div class="feature-item flex items-center">
                                <div class="feature-icon">
                                    <i class="fas fa-trophy text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold mb-2">Instrutores Certificados</h4>
                                    <p class="text-gray-300">Profissionais experientes com certificações nacionais e internacionais.</p>
                                </div>
                            </div>

                            <div class="feature-item flex items-center">
                                <div class="feature-icon">
                                    <i class="fas fa-dumbbell text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold mb-2">Equipamentos Profissionais</h4>
                                    <p class="text-gray-300">Estrutura completa com equipamentos de última geração para seu treino.</p>
                                </div>
                            </div>

                            <div class="feature-item flex items-center">
                                <div class="feature-icon">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold mb-2">Comunidade Forte</h4>
                                    <p class="text-gray-300">Faça parte de uma família unida pela paixão pelo esporte.</p>
                                </div>
                            </div>

                            <div class="feature-item flex items-center">
                                <div class="feature-icon">
                                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold mb-2">Horários Flexíveis</h4>
                                    <p class="text-gray-300">Aulas de manhã, tarde e noite para se adequar à sua rotina.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="fade-in-right">
                    @if($site->sobre_imagem)
                    <div class="grid grid-cols-1 gap-6">
                        <img src="{{ Storage::url($site->sobre_imagem) }}" 
                             alt="{{ $site->titulo }}" class="rounded-2xl shadow-2xl scale-up w-full">
                    </div>
                    @else
                    <div class="grid grid-cols-2 gap-6">
                        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Academia" class="rounded-2xl shadow-2xl scale-up">
                        <img src="https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Treino" class="rounded-2xl shadow-2xl mt-8 scale-up" style="animation-delay: 0.2s;">
                        <img src="https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Equipamentos" class="rounded-2xl shadow-2xl -mt-8 scale-up" style="animation-delay: 0.4s;">
                        <img src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                             alt="Atletas" class="rounded-2xl shadow-2xl scale-up" style="animation-delay: 0.6s;">
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Testimonials Section -->
    @if($site->depoimentos->count() > 0)
    <section id="testimonials" class="py-20" style="background: linear-gradient(135deg, var(--dark-bg) 0%, var(--darker-bg) 100%);">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="section-header text-center mb-16">
                <h2 class="fade-in-up">O Que Dizem Nossos Clientes</h2>
                <div class="section-divider fade-in-up"></div>
                <p class="text-xl opacity-80 fade-in-up">
                    Histórias reais de transformação e conquistas
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($site->depoimentos as $index => $depoimento)
                <div class="testimonial-card fade-in-up" style="animation-delay: {{ $index * 0.2 }}s;">
                    <div class="flex items-center mb-6">
                        @if($depoimento->foto)
                        <img src="{{ Storage::url($depoimento->foto) }}" 
                             alt="{{ $depoimento->nome }}" class="w-16 h-16 rounded-full mr-4 object-cover">
                        @else
                        <div class="w-16 h-16 rounded-full mr-4 bg-gradient-to-r from-red-500 to-orange-500 flex items-center justify-center">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        @endif
                        <div>
                            <h4 class="font-bold text-lg">{{ $depoimento->nome }}</h4>
                            <p class="text-gray-400">{{ $depoimento->cargo ?? 'Cliente' }} - {{ $depoimento->created_at->format('Y') }}</p>
                        </div>
                    </div>
                    @if($depoimento->nota)
                    <div class="text-yellow-400 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $depoimento->nota)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    @endif
                    <p class="text-gray-300">
                        "{{ $depoimento->depoimento }}"
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="container mx-auto px-6 relative z-10 max-w-5xl text-center">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 fade-in-up" style="font-family: 'Bebas Neue', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                Pronto para Sua Transformação?
            </h2>
            <p class="text-xl md:text-2xl mb-8 opacity-90 fade-in-up" style="animation-delay: 0.2s;">
                Junte-se aos nossos alunos que já transformaram suas vidas. Sua jornada começa hoje!
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-6 fade-in-up" style="animation-delay: 0.4s;">
                @if($site->empresa && $site->empresa->telefone)
                <a href="tel:{{ preg_replace('/[^0-9]/', '', $site->empresa->telefone) }}" 
                   class="bg-white text-gray-800 hover:bg-gray-100 px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-phone mr-2"></i>
                    {{ $site->empresa->telefone }}
                </a>
                @endif
                @if($site->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site->whatsapp) }}?text=Quero%20mais%20informações!" 
                   onclick="registrarCliqueWhatsapp()"
                   class="border-2 border-white text-white hover:bg-white hover:text-gray-800 px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 hover:scale-105">
                    <i class="fab fa-whatsapp mr-2"></i>
                    Falar no WhatsApp
                </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20" style="background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="section-header text-center mb-16">
                <h2 class="fade-in-up">Entre em Contato</h2>
                <div class="section-divider fade-in-up"></div>
                <p class="text-xl opacity-80 fade-in-up">
                    Estamos aqui para tirar suas dúvidas e ajudar você a começar
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-16">
                <!-- Contact Info -->
                <div class="fade-in-left">
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-8 rounded-2xl border border-gray-700">
                        <h3 class="text-3xl font-bold mb-8" style="font-family: 'Bebas Neue', cursive;">Informações de Contato</h3>
                        
                        <div class="space-y-6">
                            @if($site->endereco)
                            <div class="flex items-start">
                                <div class="bg-gradient-to-r from-red-500 to-orange-500 p-4 rounded-full mr-6 flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xl mb-2">Endereço</h4>
                                    <p class="text-gray-300 text-lg">
                                        {{ $site->endereco->logradouro }}, {{ $site->endereco->numero }}<br>
                                        {{ $site->endereco->bairro }} - {{ $site->endereco->cidade }}, {{ $site->endereco->uf }}<br>
                                        CEP: {{ $site->endereco->cep }}
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if($site->empresa && $site->empresa->telefone)
                            <div class="flex items-start">
                                <div class="bg-gradient-to-r from-red-500 to-orange-500 p-4 rounded-full mr-6 flex-shrink-0">
                                    <i class="fas fa-phone text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xl mb-2">Telefone</h4>
                                    <p class="text-gray-300 text-lg">{{ $site->empresa->telefone }}</p>
                                </div>
                            </div>
                            @endif

                            @if($site->empresa && $site->empresa->email)
                            <div class="flex items-start">
                                <div class="bg-gradient-to-r from-red-500 to-orange-500 p-4 rounded-full mr-6 flex-shrink-0">
                                    <i class="fas fa-envelope text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xl mb-2">Email</h4>
                                    <p class="text-gray-300 text-lg">{{ $site->empresa->email }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="flex items-start">
                                <div class="bg-gradient-to-r from-red-500 to-orange-500 p-4 rounded-full mr-6 flex-shrink-0">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xl mb-2">Horários</h4>
                                    <p class="text-gray-300 text-lg">
                                        Seg - Sex: 6h às 22h<br>
                                        Sáb: 7h às 18h<br>
                                        Dom: 8h às 16h
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="fade-in-right">
                    <form class="bg-gradient-to-br from-gray-800 to-gray-900 p-8 rounded-2xl border border-gray-700" id="contactForm">
                        @csrf
                        <h3 class="text-3xl font-bold mb-8" style="font-family: 'Bebas Neue', cursive;">Envie uma Mensagem</h3>
                        
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-gray-300 font-semibold mb-3 text-lg">Nome</label>
                                <input type="text" name="nome" class="w-full px-4 py-4 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 text-white" required>
                            </div>
                            <div>
                                <label class="block text-gray-300 font-semibold mb-3 text-lg">Telefone</label>
                                <input type="tel" name="telefone" class="w-full px-4 py-4 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 text-white" required>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-300 font-semibold mb-3 text-lg">Email</label>
                            <input type="email" name="email" class="w-full px-4 py-4 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 text-white" required>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-300 font-semibold mb-3 text-lg">Serviço de Interesse</label>
                            <select name="servico" class="w-full px-4 py-4 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 text-white">
                                <option value="">Selecione um serviço</option>
                                @php
                                    $servicos = $site->siteServicos->count() > 0 ? $site->siteServicos : $site->servicos;
                                @endphp
                                @foreach($servicos as $servico)
                                <option value="{{ $servico->nome ?? $servico->titulo }}">{{ $servico->nome ?? $servico->titulo }}</option>
                                @endforeach
                                <option value="Informações Gerais">Informações Gerais</option>
                            </select>
                        </div>
                        
                        <div class="mb-8">
                            <label class="block text-gray-300 font-semibold mb-3 text-lg">Mensagem</label>
                            <textarea name="mensagem" rows="5" class="w-full px-4 py-4 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300 text-white resize-none" placeholder="Conte-nos mais sobre seu interesse..." required></textarea>
                        </div>
                        
                        <button type="submit" class="w-full btn-primary text-white font-bold py-4 rounded-lg text-lg transition-all duration-300 hover:scale-105">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-16">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="text-center mb-12">
                <div class="logo text-4xl font-bold mb-6">{{ $site->titulo ?? 'Fight Club Academy' }}</div>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    {{ $site->descricao ?? 'Transformando vidas há mais de 10 anos. Nossa missão é desenvolver não apenas atletas, mas pessoas mais disciplinadas, confiantes e determinadas.' }}
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-12 mb-12">
                <!-- Quick Links -->
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-bold mb-6 text-orange-400">Links Rápidos</h3>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-400 hover:text-orange-400 transition-colors text-lg">Início</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-orange-400 transition-colors text-lg">Serviços</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-orange-400 transition-colors text-lg">Sobre</a></li>
                        @if($site->depoimentos->count() > 0)
                        <li><a href="#testimonials" class="text-gray-400 hover:text-orange-400 transition-colors text-lg">Depoimentos</a></li>
                        @endif
                        <li><a href="#contact" class="text-gray-400 hover:text-orange-400 transition-colors text-lg">Contato</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-bold mb-6 text-orange-400">Contato</h3>
                    <ul class="space-y-3 text-gray-400 text-lg">
                        @if($site->endereco)
                        <li><i class="fas fa-map-marker-alt mr-3 text-orange-400"></i>{{ $site->endereco->logradouro }}, {{ $site->endereco->numero }} - {{ $site->endereco->bairro }}</li>
                        @endif
                        @if($site->empresa && $site->empresa->telefone)
                        <li><i class="fas fa-phone mr-3 text-orange-400"></i>{{ $site->empresa->telefone }}</li>
                        @endif
                        @if($site->empresa && $site->empresa->email)
                        <li><i class="fas fa-envelope mr-3 text-orange-400"></i>{{ $site->empresa->email }}</li>
                        @endif
                    </ul>
                </div>

                <!-- Social Media -->
                <div class="text-center md:text-left">
                    <h3 class="text-2xl font-bold mb-6 text-orange-400">Redes Sociais</h3>
                    <div class="flex justify-center md:justify-start space-x-6">
                        <a href="#" class="text-gray-400 hover:text-orange-400 transition-all duration-300 hover:scale-110">
                            <i class="fab fa-facebook text-3xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-orange-400 transition-all duration-300 hover:scale-110">
                            <i class="fab fa-instagram text-3xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-orange-400 transition-all duration-300 hover:scale-110">
                            <i class="fab fa-youtube text-3xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-orange-400 transition-all duration-300 hover:scale-110">
                            <i class="fab fa-tiktok text-3xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 text-center">
                <p class="text-gray-400 text-lg">&copy; {{ date('Y') }} {{ $site->titulo ?? 'Fight Club Academy' }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Loading Screen
        window.addEventListener('load', function() {
            setTimeout(() => {
                const loading = document.getElementById('loading');
                loading.style.opacity = '0';
                setTimeout(() => {
                    loading.style.display = 'none';
                    // Start animations after loading
                    initScrollAnimations();
                }, 800);
            }, 2500);
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('header-scrolled');
            } else {
                header.classList.remove('header-scrolled');
            }
        });

        // Mobile Navigation
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('nav-menu');

        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        // Close menu when clicking on a link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });

        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            if (slides[index]) {
                slides[index].classList.add('active');
            }
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        // Auto-advance carousel if there are multiple slides
        if (totalSlides > 1) {
            setInterval(nextSlide, 4000);
        }

        // Scroll animations with GSAP
        function initScrollAnimations() {
            gsap.registerPlugin(ScrollTrigger);

            // Hero animations
            gsap.timeline()
                .from(".fade-in-up", {
                    duration: 1,
                    y: 60,
                    opacity: 0,
                    stagger: 0.2,
                    ease: "power3.out"
                })
                .from(".fade-in-right", {
                    duration: 1,
                    x: 60,
                    opacity: 0,
                    ease: "power3.out"
                }, "-=0.5");

            // Services animation
            gsap.from(".service-card", {
                duration: 0.8,
                y: 80,
                opacity: 0,
                stagger: 0.15,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: "#services",
                    start: "top 80%"
                }
            });

            // About section animations
            gsap.from(".fade-in-left", {
                duration: 1,
                x: -60,
                opacity: 0,
                scrollTrigger: {
                    trigger: "#about",
                    start: "top 80%"
                }
            });

            gsap.from(".scale-up", {
                duration: 0.8,
                scale: 0.8,
                opacity: 0,
                stagger: 0.1,
                scrollTrigger: {
                    trigger: "#about",
                    start: "top 70%"
                }
            });

            gsap.from(".feature-item", {
                duration: 0.6,
                x: -40,
                opacity: 0,
                stagger: 0.1,
                scrollTrigger: {
                    trigger: ".feature-item",
                    start: "top 85%"
                }
            });

            // Testimonials animation
            gsap.from(".testimonial-card", {
                duration: 0.8,
                y: 60,
                opacity: 0,
                stagger: 0.1,
                scrollTrigger: {
                    trigger: "#testimonials",
                    start: "top 80%"
                }
            });

            // Contact section animations
            gsap.from("#contact .fade-in-left", {
                duration: 0.8,
                x: -60,
                opacity: 0,
                scrollTrigger: {
                    trigger: "#contact",
                    start: "top 80%"
                }
            });

            gsap.from("#contact .fade-in-right", {
                duration: 0.8,
                x: 60,
                opacity: 0,
                scrollTrigger: {
                    trigger: "#contact",
                    start: "top 80%"
                }
            });
        }

        // WhatsApp functionality
        function openWhatsApp(service = '') {
            @if($site->whatsapp)
            const phoneNumber = "{{ preg_replace('/[^0-9]/', '', $site->whatsapp) }}";
            let message = "Olá! Gostaria de mais informações sobre {{ $site->titulo ?? 'seus serviços' }}!";
            
            if (service) {
                message = `Olá! Gostaria de mais informações sobre: ${service}`;
            }
            
            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/${phoneNumber}?text=${encodedMessage}`, '_blank');
            
            // Registrar clique
            registrarCliqueWhatsapp();
            @endif
        }

        // Registrar clique no WhatsApp
  

        // Contact form submission
        

          
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Notification function
        function showNotification(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            
            const notification = document.createElement('div');
            notification.innerHTML = `
                <div class="fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full max-w-sm">
                    <div class="flex items-center">
                        <i class="${icon} mr-2"></i>
                        <span class="text-sm">${message}</span>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            const notificationEl = notification.querySelector('div');
            
            // Show notification
            setTimeout(() => {
                notificationEl.classList.remove('translate-x-full');
            }, 100);
            
            // Hide notification
            setTimeout(() => {
                notificationEl.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.hero');
            
            parallaxElements.forEach(element => {
                const speed = 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Add hover effects to service cards
        document.querySelectorAll('.service-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                gsap.to(this, {
                    duration: 0.3,
                    scale: 1.05,
                    boxShadow: "0 20px 40px rgba(255, 68, 68, 0.2)",
                    ease: "power2.out"
                });
            });
            
            card.addEventListener('mouseleave', function() {
                gsap.to(this, {
                    duration: 0.3,
                    scale: 1,
                    boxShadow: "none",
                    ease: "power2.out"
                });
            });
        });

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .scale-up').forEach(el => {
                observer.observe(el);
            });
        });

        // Add dynamic background particles
        function createParticle() {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.width = Math.random() * 4 + 1 + 'px';
            particle.style.height = particle.style.width;
            particle.style.background = `rgba(255, ${Math.random() * 100 + 68}, 53, ${Math.random() * 0.3 + 0.1})`;
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '1';
            particle.style.left = Math.random() * window.innerWidth + 'px';
            particle.style.top = window.innerHeight + 'px';
            
            document.body.appendChild(particle);
            
            gsap.to(particle, {
                duration: Math.random() * 10 + 5,
                y: -window.innerHeight - 100,
                x: (Math.random() - 0.5) * 200,
                opacity: 0,
                rotation: Math.random() * 360,
                ease: "none",
                onComplete: () => {
                    if (particle.parentNode) {
                        particle.parentNode.removeChild(particle);
                    }
                }
            });
        }

        // Create particles periodically
        setInterval(createParticle, 2000);

        // Add typing effect to hero title
        function typeEffect() {
            const title = document.querySelector('.hero-title');
            if (title) {
                const text = title.textContent;
                title.textContent = '';
                title.style.opacity = '1';
                
                let i = 0;
                const timer = setInterval(() => {
                    if (i < text.length) {
                        title.textContent += text.charAt(i);
                        i++;
                    } else {
                        clearInterval(timer);
                    }
                }, 100);
            }
        }

        // Initialize typing effect after loading
        window.addEventListener('load', () => {
            setTimeout(typeEffect, 3000);
        });

        // Add scroll progress indicator
        function createScrollProgress() {
            const progressBar = document.createElement('div');
            progressBar.style.position = 'fixed';
            progressBar.style.top = '0';
            progressBar.style.left = '0';
            progressBar.style.width = '0%';
            progressBar.style.height = '3px';
            progressBar.style.background = 'linear-gradient(45deg, var(--primary-color), var(--secondary-color))';
            progressBar.style.zIndex = '9999';
            progressBar.style.transition = 'width 0.1s ease';
            
            document.body.appendChild(progressBar);
            
            window.addEventListener('scroll', () => {
                const scrollPercent = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
                progressBar.style.width = Math.min(scrollPercent, 100) + '%';
            });
        }

        // Initialize scroll progress
        createScrollProgress();

        // Registrar visualização da página
        

        console.log('🥊 {{ $site->titulo ?? "Fight Club Academy" }} - Site carregado com sucesso!');
        console.log('💪 Desenvolvido para transformar vidas!');
    </script>
  <script src="{{ asset('js/site-metrics.js') }}"></script>
</body>
</html>