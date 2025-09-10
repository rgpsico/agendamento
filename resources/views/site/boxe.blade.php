<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $site->titulo ?? 'Fight Club Academy' }} - Transforme-se Através do Boxe</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Arial', sans-serif;
            background: #0a0a0a;
            color: #fff;
            overflow-x: hidden;
        }

        /* Loading Screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, {{ $site->cores['primaria'] ?? '#ff6b35' }}, {{ $site->cores['secundaria'] ?? '#f7931e' }});
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .wave-loader {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, {{ $site->cores['primaria'] ?? '#ff6b35' }}, {{ $site->cores['secundaria'] ?? '#f7931e' }});
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-menu a:hover {
            color: {{ $site->cores['primaria'] ?? '#ff6b35' }};
        }

        /* Hero Section */
        .hero-gradient {
            background: linear-gradient(135deg, {{ $site->cores['primaria'] ?? '#ff6b35' }} 0%, {{ $site->cores['secundaria'] ?? '#f7931e' }} 50%, {{ $site->cores['primaria'] ?? '#ff4500' }} 100%);
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .wave-animation {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="%23ffffff"></path><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="%23ffffff"></path><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%23ffffff"></path></svg>') repeat-x;
            animation: wave 20s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes wave {
            0%, 100% { transform: translateX(0px); }
            50% { transform: translateX(-50px); }
        }

        .text-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }

        .btn-primary {
            background: linear-gradient(45deg, #1a1a1a, #333);
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #333, #555);
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Carousel */
        .carousel-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .carousel-wrapper {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
            border-radius: 1rem;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .carousel-slide.active {
            opacity: 1;
            transform: translateX(0);
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .carousel-nav {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Services Section */
        .card-hover {
            /* transition: opacity 0.3s ease, transform 0.3s ease;
            opacity: 1;
            visibility: visible; */
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 107, 53, 0.2);
        }

        .service-icon {
            background: linear-gradient(45deg, {{ $site->cores['primaria'] ?? '#ff6b35' }}, {{ $site->cores['secundaria'] ?? '#f7931e' }});
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* About Section */
        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* WhatsApp Button */
        .diagonal-whatsapp {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 30px;
            right: 30px;
            background: #25d366;
            color: white;
            border-radius: 50px;
            text-align: center;
            font-size: 28px;
            box-shadow: 2px 2px 15px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }

        .diagonal-whatsapp:hover {
            transform: scale(1.1);
            box-shadow: 2px 2px 25px rgba(37, 211, 102, 0.6);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        /* Section Divider */
        .section-divider {
            height: 4px;
            background: linear-gradient(45deg, {{ $site->cores['primaria'] ?? '#ff6b35' }}, {{ $site->cores['secundaria'] ?? '#f7931e' }});
            border-radius: 2px;
            margin: 1rem auto;
        }

        /* Testimonial Cards */
        .testimonial-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Stars */
        .stars {
            color: #fbbf24;
        }

        /* Scroll Indicator */
        .scroll-indicator {
            z-index: 10;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }
            
            .carousel-wrapper {
                height: 300px;
            }

            .hero-content h1 {
                font-size: 2.5rem !important;
            }

            .hero-buttons {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr !important;
            }

            .carousel-wrapper {
                height: 250px;
            }
        }
    </style>
</head>
<body class="font-sans bg-gray-50 overflow-x-hidden">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loading">
        <div class="wave-loader"></div>
        <p class="text-white text-xl mt-4 loading-text">Preparando o treino...</p>
    </div>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/{{ $site->whatsapp ?? '5511999999999' }}?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20os%20treinos%20de%20boxe!" class="diagonal-whatsapp floating">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Navigation -->
    <header id="header">
        <div class="nav-container">
            <div class="logo">{{ $site->titulo ?? 'Fight Club Academy' }}</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#home">Início</a></li>
                    <li><a href="#services">Serviços</a></li>
                    <li><a href="#about">Sobre</a></li>
                    <li><a href="#testimonials">Depoimentos</a></li>
                    <li><a href="#contact">Contato</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient text-white min-h-screen flex items-center relative">
        <div class="wave-animation"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="hero-content">
                    <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-6 text-glow">
                        <span class="typing-text" id="hero-title">{{ $site->descricao ?? 'Desperte o Lutador que Existe em Você' }}</span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 opacity-90 hero-subtitle" id="hero-text">
                        {{ $site->sobre_descricao ?? 'Transforme sua vida através do boxe. Treine com os melhores, desenvolva disciplina, força e confiança. Sua jornada de transformação começa agora!' }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 hero-buttons">
                        <a href="#services" class="btn-primary text-white px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all duration-300">
                            <i class="fas fa-fist-raised mr-2"></i>
                            Comece Sua Transformação
                        </a>
                        <a href="#about" class="border-2 border-white text-white hover:bg-white hover:text-gray-800 px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300">
                            <i class="fas fa-play mr-2"></i>
                            Ver Mais
                        </a>
                    </div>
                </div>
                
                <!-- Image Carousel -->
                <div class="hero-image relative">
                    <div class="floating carousel-container">
                        <div class="carousel-wrapper" id="carousel">
                            @forelse ($site->servicos as $index => $servico)
                                <div class="carousel-slide {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . ($servico->capa ?? $site->capa)) }}" 
                                         alt="{{ $servico->titulo ?? 'Imagem de Serviço' }}" class="rounded-2xl shadow-2xl w-full h-96 object-cover">
                                </div>
                            @empty
                                <div class="carousel-slide active">
                                    <img src="{{ asset('storage/' . ($site->capa ?? 'default-capa.jpg')) }}" 
                                         alt="Imagem Padrão" class="rounded-2xl shadow-2xl w-full h-96 object-cover">
                                </div>
                            @endforelse
                        </div>
                        
                        <!-- Carousel Navigation -->
                        <div class="carousel-nav">
                            @forelse ($site->servicos as $index => $servico)
                                <button class="carousel-dot {{ $index == 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></button>
                            @empty
                                <button class="carousel-dot active"></button>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 scroll-indicator">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-bounce"></div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 section-header">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Nossos Serviços
                </h2>
                <div class="section-divider max-w-md mx-auto"></div>
                <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                    {{ $site->sobre_descricao ?? 'Oferecemos uma experiência completa para todos os níveis - do iniciante ao profissional.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($site->siteServicos as $servico)
                    <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg relative">
                        @if ($servico->destaque)
                            <div class="absolute top-4 left-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold z-10">
                                Mais Popular
                            </div>
                        @endif
                        <div class="h-48 relative overflow-hidden">
                       
                              <img 
                        src="{{ Storage::url($servico->imagem) }}" 
                        alt="{{ $servico->titulo }}" 
                        class="preview-img-small"
                    />

                            
                            <div class="absolute top-4 right-4 bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-full p-3">
                                <i class="fas {{ $servico->icone ?? 'fa-user' }} text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $servico->titulo }}</h3>
                            <p class="text-gray-600 mb-4">{{ $servico->descricao }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-3xl font-bold service-icon">{{ $servico->preco ? 'R$ ' . number_format($servico->preco, 2, ',', '.') : 'Consultar' }}</span>
                                <button class="btn-primary text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 hover:scale-105" onclick="openWhatsApp('{{ $servico->titulo }}')">
                                    Reservar
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-3">Nenhum serviço disponível no momento.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white parallax-bg" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ asset('storage/' . ($site->sobre_imagem ?? 'default-sobre.jpg')) }}');">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="about-content">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 about-title">{{ $site->sobre_titulo ?? 'Por que escolher a Fight Club Academy?' }}</h2>
                    <p class="text-gray-200 text-lg mb-8 about-description">{{ $site->sobre_descricao ?? 'Com mais de 10 anos de experiência, somos a academia de boxe líder na região, formando campeões e transformando vidas.' }}</p>
                    
                    <div class="space-y-6 about-features">
                        @foreach ($site->sobre_itens ?? [] as $item)
                            <div class="flex items-start glass-card p-4 rounded-lg about-feature">
                                <div class="bg-orange-500 p-3 rounded-full mr-4 flex-shrink-0">
                                    <i class="fas {{ $item['icone'] ?? 'fa-medal' }} text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white mb-2">{{ $item['titulo'] ?? 'Característica' }}</h3>
                                    <p class="text-gray-200">{{ $item['descricao'] ?? 'Descrição da característica.' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="about-image">
                    <div class="grid grid-cols-2 gap-4">
                        @forelse ($site->siteServicos as $index => $siteServico)
                            <img 
                                src="{{ asset('storage/sites/servicos/' . ($siteServico->imagem ?? 'default-servico.jpg')) }}" 
                                alt="{{ $siteServico->titulo }}" 
                                class="rounded-lg shadow-lg about-image-item {{ $index % 2 == 1 ? 'mt-8' : ($index == 2 ? 'mt-16' : '') }}">
                        @empty
                            <img 
                                src="{{ asset('storage/' . ($site->sobre_imagem ?? 'default-sobre.jpg')) }}" 
                                alt="Imagem Padrão" 
                                class="rounded-lg shadow-lg about-image-item">
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 section-header">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">O Que Dizem Nossos Alunos</h2>
                <div class="section-divider max-w-md mx-auto"></div>
                <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                    Histórias reais de transformação e conquistas
                </p>
            </div>
        
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 testimonials-grid">
                @forelse ($site->depoimentos as $depoimento)
                    <div class="testimonial-card">
                        <div class="flex items-center mb-4">
                            <img src="{{ $depoimento->imagem ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}" alt="{{ $depoimento->nome }}" class="w-12 h-12 rounded-full mr-4 object-cover">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $depoimento->nome }}</h4>
                                <p class="text-sm text-gray-600">{{ $depoimento->servico ?? 'Serviço' }} - {{ $depoimento->ano ?? now()->year }}</p>
                            </div>
                        </div>
                        <div class="stars mb-4">
                            @for ($i = 0; $i < ($depoimento->avaliacao ?? 5); $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-gray-700 mb-4">"{{ $depoimento->texto ?? 'Depoimento inspirador de um aluno.' }}"</p>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-3">Nenhum depoimento disponível no momento.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 hero-gradient text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 text-glow cta-title">Pronto para Sua Primeira Luta?</h2>
            <p class="text-xl md:text-2xl mb-8 opacity-90 cta-subtitle">{{ $site->sobre_descricao ?? 'Junte-se a centenas de alunos que já transformaram suas vidas através do boxe' }}</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 cta-buttons">
                <button onclick="openWhatsApp('Informações gerais')" class="bg-white text-gray-800 hover:bg-gray-100 px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all duration-300">
                    <i class="fas fa-phone mr-2"></i>
                    {{ $site->whatsapp ?? '(11) 99999-9999' }}
                </button>
                <button onclick="openWhatsApp('Quero treinar boxe')" class="border-2 border-white text-white hover:bg-white hover:text-gray-800 px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300">
                    <i class="fab fa-whatsapp mr-2"></i>
                    WhatsApp
                </button>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16 section-header">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Fale Conosco</h2>
                <div class="section-divider max-w-md mx-auto"></div>
                <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                    Estamos aqui para tirar suas dúvidas e ajudar você a começar sua jornada no boxe
                </p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Contact Info -->
                    <div class="contact-info">
                        <div class="glass-card p-8 rounded-2xl bg-gradient-to-br from-orange-50 to-red-50">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Informações de Contato</h3>
                            <div class="space-y-6">
                                <!-- Address -->
                                <div class="flex items-start">
                                    <div class="bg-orange-500 p-3 rounded-full mr-4 flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1">Endereço</h4>
                                        <p class="text-gray-600">
                                            {{ $site->endereco->rua ?? 'Rua dos Lutadores, 1234' }}<br>
                                            {{ $site->endereco->bairro ?? 'Centro' }} - {{ $site->endereco->cidade ?? 'São Paulo' }}, {{ $site->endereco->estado ?? 'SP' }}<br>
                                            {{ $site->endereco->cep ?? '01000-000' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="flex items-start">
                                    <div class="bg-orange-500 p-3 rounded-full mr-4 flex-shrink-0">
                                        <i class="fas fa-phone text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1">Telefone</h4>
                                        <p class="text-gray-600">{{ $site->whatsapp ?? '(11) 99999-9999' }}</p>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="flex items-start">
                                    <div class="bg-orange-500 p-3 rounded-full mr-4 flex-shrink-0">
                                        <i class="fas fa-envelope text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1">Email</h4>
                                        <p class="text-gray-600">{{ $site->empresa->email ?? 'contato@fightclub.com.br' }}</p>
                                    </div>
                                </div>

                                <!-- Schedule -->
                                <div class="flex items-start">
                                    <div class="bg-orange-500 p-3 rounded-full mr-4 flex-shrink-0">
                                        <i class="fas fa-clock text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1">Horário</h4>
                                        <p class="text-gray-600">
                                            {{ $site->horario ?? 'Seg - Sex: 6h às 22h<br>Sáb: 7h às 18h<br>Dom: 8h às 16h' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="contact-form">
                        <form class="space-y-6" id="contactForm">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Nome</label>
                                    <input type="text" name="nome" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-300" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Telefone</label>
                                    <input type="tel" name="telefone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-300" required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-300" required>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Serviço de Interesse</label>
                                <select name="servico" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-300">
                                    @forelse ($site->servicos as $servico)
                                        <option>{{ $servico->titulo }}</option>
                                    @empty
                                        <option>Informações Gerais</option>
                                    @endforelse
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Mensagem</label>
                                <textarea name="mensagem" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all duration-300" placeholder="Conte-nos mais sobre seu interesse no boxe..." required></textarea>
                            </div>
                            
                            <button type="submit" class="w-full btn-primary text-white font-semibold py-4 rounded-lg hover:scale-105 transition-all duration-300">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Enviar Mensagem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

      @if($site->atendimento_com_ia)
    <x-batepapo />
    @endif


       @if($site->atendimento_com_whatsapp)
            <x-atendimentowhatsapp :numero="$site->whatsapp" mensagem="Olá! Gostaria de atendimento." />
       @endif

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="logo text-2xl font-bold mb-4">{{ $site->titulo ?? 'Fight Club Academy' }}</div>
                    <p class="text-gray-400 mb-4">{{ $site->descricao ?? 'Transformando vidas através do boxe há mais de 10 anos. Nossa missão é desenvolver não apenas lutadores, mas pessoas mais disciplinadas e confiantes.' }}</p>
                    <div class="flex space-x-4">
                        <a href="{{ $site->facebook ?? '#' }}" class="text-gray-400 hover:text-orange-500 transition-colors">
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a href="{{ $site->instagram ?? '#' }}" class="text-gray-400 hover:text-orange-500 transition-colors">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        <a href="{{ $site->youtube ?? '#' }}" class="text-gray-400 hover:text-orange-500 transition-colors">
                            <i class="fab fa-youtube text-2xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-400 hover:text-orange-500 transition-colors">Início</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-orange-500 transition-colors">Serviços</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-orange-500 transition-colors">Sobre</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-orange-500 transition-colors">Depoimentos</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-orange-500 transition-colors">Contato</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contato</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-map-marker-alt mr-2"></i>{{ $site->endereco->rua ?? 'Rua dos Lutadores, 1234' }}</li>
                        <li><i class="fas fa-phone mr-2"></i>{{ $site->whatsapp ?? '(11) 99999-9999' }}</li>
                        <li><i class="fas fa-envelope mr-2"></i>{{ $site->empresa->email ?? 'contato@fightclub.com.br' }}</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ now()->year }} {{ $site->titulo ?? 'Fight Club Academy' }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Loading Screen
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loading').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('loading').style.display = 'none';
                }, 500);
            }, 2000);
        });

        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        const totalSlides = slides.length;

        function goToSlide(slideIndex) {
            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');
            
            currentSlide = slideIndex;
            
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            const nextIndex = (currentSlide + 1) % totalSlides;
            goToSlide(nextIndex);
        }

        function startCarousel() {
            setInterval(nextSlide, 4000);
        }

        // GSAP Animations
        gsap.registerPlugin(ScrollTrigger);

        // Hero animations
        gsap.timeline()
            .from(".hero-content", {
                duration: 1,
                x: -100,
                opacity: 0,
                ease: "power3.out"
            })
            .from(".hero-image", {
                duration: 1,
                x: 100,
                opacity: 0,
                ease: "power3.out"
            }, "-=0.5")
            .from(".scroll-indicator", {
                duration: 0.8,
                y: 50,
                opacity: 0,
                ease: "power2.out"
            }, "-=0.3");

        // Services animation
        gsap.from(".card-hover", {
            duration: 0.8,
            y: 100,
            opacity: 0,
            stagger: 0.2,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#services",
                start: "top 80%"
            }
        });

        // About animation
        gsap.from(".about-feature", {
            duration: 0.8,
            x: -50,
            opacity: 0,
            stagger: 0.2,
            scrollTrigger: {
                trigger: "#about",
                start: "top 80%"
            }
        });

        gsap.from(".about-image-item", {
            duration: 0.8,
            scale: 0.8,
            opacity: 0,
            stagger: 0.1,
            scrollTrigger: {
                trigger: "#about",
                start: "top 80%"
            }
        });

        // Testimonials animation
        gsap.from(".testimonial-card", {
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            scrollTrigger: {
                trigger: "#testimonials",
                start: "top 80%"
            }
        });

        // WhatsApp functionality
        function openWhatsApp(service = '') {
            const phoneNumber = "{{ $site->whatsapp ?? '5511999999999' }}";
            let message = "Olá! Gostaria de mais informações sobre os treinos de boxe!";
            
            if (service) {
                message = `Olá! Gostaria de mais informações sobre: ${service}`;
            }
            
            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/${phoneNumber}?text=${encodedMessage}`, '_blank');
        }

        // Contact form
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const nome = formData.get('nome');
            const telefone = formData.get('telefone');
            const email = formData.get('email');
            const servico = formData.get('servico');
            const mensagem = formData.get('mensagem');
            
            const message = `Olá! Meu nome é ${nome}.
Telefone: ${telefone}
Email: ${email}
Serviço: ${servico}
Mensagem: ${mensagem}`;

            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/{{ $site->whatsapp ?? '5511999999999' }}?text=${encodedMessage}`, '_blank');
        });

        // Smooth scrolling
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

        // Initialize carousel
        document.addEventListener('DOMContentLoaded', function() {
            if (totalSlides > 0) {
                startCarousel();
            }
        });

        // Typing animation
        function typeWriter(element, text, speed = 100) {
            element.innerHTML = '';
            element.style.borderRight = '3px solid white';
            
            let i = 0;
            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                } else {
                    setTimeout(() => {
                        element.style.borderRight = 'none';
                    }, 1000);
                }
            }
            type();
        }

        // Initialize typing animation
        window.addEventListener('load', function() {
            setTimeout(() => {
                const titleElement = document.getElementById('hero-title');
                const originalText = titleElement.textContent;
                typeWriter(titleElement, originalText, 80);
            }, 2500);
        });
    </script>
</body>
</html>