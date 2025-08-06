<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $site->titulo }} - {{ $site->descricao ?? 'Escola de Surf' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="empresa-id" content="{{ $site->empresa_id ?? 1 }}">

    @if(!empty($site->favicon))
        <link rel="icon" href="{{ asset('storage/' . $site->favicon) }}" type="image/png">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>

    @php
        $corPrimaria = $site->cores['primaria'] ?? '#667eea';
        $corSecundaria = $site->cores['secundaria'] ?? '#764ba2';
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        * { font-family: 'Poppins', sans-serif; }

        .hero-gradient {
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="40%"><stop offset="0%" style="stop-color:rgb(255,255,255);stop-opacity:0.3"/><stop offset="100%" style="stop-color:rgb(255,255,255);stop-opacity:0"/></radialGradient></defs><ellipse fill="url(%23a)" cx="50" cy="10" rx="50" ry="10"/></svg>') repeat-x;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .wave-animation {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="%23ffffff"/></svg>') no-repeat center bottom;
            background-size: cover;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .floating { animation: float 3s ease-in-out infinite; }

        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .text-glow { text-shadow: 0 0 20px rgba(255, 255, 255, 0.5); }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-hover:hover {
            transform: translateY(-15px) scale(1.02);
        }

        .diagonal-whatsapp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            box-shadow: 0 10px 30px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .diagonal-whatsapp:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 40px rgba(37, 211, 102, 0.6);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -5px;
            left: 50%;
            background: linear-gradient(90deg, {{ $corPrimaria }}, {{ $corSecundaria }});
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-link:hover::before { width: 100%; }

        .service-icon {
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before { left: 100%; }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            flex-direction: column;
        }

        .wave-loader {
            width: 100px;
            height: 100px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stagger-item {
            opacity: 0;
            transform: translateY(50px) !important;
            
        }

        .testimonial-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-left: 4px solid transparent;
            background-clip: padding-box;
            position: relative;
        }

        .testimonial-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(135deg, {{ $corPrimaria }} 0%, {{ $corSecundaria }} 100%);
            border-radius: 0 2px 2px 0;
        }

        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, {{ $corPrimaria }} 50%, transparent 100%);
            margin: 2rem 0;
        }
    </style>
</head>

<body class="font-sans bg-gray-50 overflow-x-hidden">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loading">
        <div class="wave-loader"></div>
        <p class="text-white text-xl mt-4 loading-text">Preparando as ondas...</p>
    </div>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/{{ $site->telefone ?? '5511999999999' }}?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20seus%20serviços" class="diagonal-whatsapp floating">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center nav-logo">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-water text-2xl service-icon"></i>
                    </div>
                    <span class="text-white text-2xl font-bold">{{ $site->titulo }}</span>
                </div>
                <div class="hidden md:flex space-x-8 nav-menu">
                    <a href="#home" class="nav-link text-white hover:text-gray-200">Início</a>
                    <a href="#services" class="nav-link text-white hover:text-gray-200">Serviços</a>
                    <a href="#about" class="nav-link text-white hover:text-gray-200">Sobre</a>
                    <a href="#testimonials" class="nav-link text-white hover:text-gray-200">Depoimentos</a>
                    <a href="#contact" class="nav-link text-white hover:text-gray-200">Contato</a>
                </div>
                <button class="md:hidden focus:outline-none mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars text-white text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="fixed inset-0 z-40 transform -translate-x-full transition-transform duration-300 md:hidden" id="mobileMenu">
        <div class="glass-card h-full w-64 p-6">
            <div class="flex justify-end mb-8">
                <button class="text-white" id="closeMobileMenu">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="space-y-6">
                <a href="#home" class="block text-white text-lg mobile-nav-link">Início</a>
                <a href="#services" class="block text-white text-lg mobile-nav-link">Serviços</a>
                <a href="#about" class="block text-white text-lg mobile-nav-link">Sobre</a>
                <a href="#testimonials" class="block text-white text-lg mobile-nav-link">Depoimentos</a>
                <a href="#contact" class="block text-white text-lg mobile-nav-link">Contato</a>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="hero-gradient text-white min-h-screen flex items-center relative">
        <div class="wave-animation"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="hero-content">
                    <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-6 text-glow">
                        Domine as <span class="typing-text"></span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 opacity-90 hero-subtitle">
                        {{ $site->descricao ?? 'Aprenda a surfar com os melhores instrutores e viva a experiência única do oceano' }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 hero-buttons">
                        <button class="btn-primary text-white px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all duration-300">
                            <i class="fas fa-water mr-2"></i>
                            Começar Agora
                        </button>
                        <button class="border-2 border-white text-white hover:bg-white hover:text-gray-800 px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300">
                            <i class="fas fa-play mr-2"></i>
                            Ver Vídeo
                        </button>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="floating">
                        @if($site->capa)
                            <img src="{{ asset('storage/' . $site->capa) }}" alt="Surfista" class="rounded-2xl shadow-2xl w-full">
                        @else
                            <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Surfista" class="rounded-2xl shadow-2xl w-full">
                        @endif
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

    <!-- Stats Section -->
    <section class="py-20 bg-white relative">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 stats-container">
                @foreach($site->estatisticas ?? [
                    ['valor' => 500, 'texto' => 'Alunos Formados'],
                    ['valor' => 15, 'texto' => 'Anos de Experiência'],
                    ['valor' => 98, 'texto' => '% Satisfação'],
                    ['valor' => 24, 'texto' => 'Instrutores Certificados']
                ] as $estatistica)
                    <div class="text-center stagger-item">
                        <div class="text-4xl md:text-6xl font-bold service-icon counter" data-target="{{ $estatistica['valor'] }}">0</div>
                        <p class="text-gray-600 text-lg mt-2">{{ $estatistica['texto'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 section-header">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">{{ $site->servicos_titulo ?? 'Nossos Serviços' }}</h2>
            <div class="section-divider max-w-md mx-auto"></div>
            <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                {{ $site->servicos_descricao ?? 'Oferecemos uma experiência completa para todos os níveis, do iniciante ao avançado' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 services-grid">
            @if($site->servicos->isEmpty())
                @php
                    $fakeServices = [
                        [
                            'titulo' => 'Aula Individual',
                            'descricao' => 'Atenção personalizada com um instrutor dedicado exclusivamente para você.',
                            'preco' => 150.00,
                            'icone' => 'fa-user',
                            'features' => [
                                'Equipamento incluído',
                                '2 horas de aula',
                                'Teoria e prática',
                                'Certificado'
                            ],
                            'destaque' => false,
                            'imagem' => 'https://picsum.photos/500/300?random=1'
                        ],
                        [
                            'titulo' => 'Aula em Grupo',
                            'descricao' => 'Aprenda em um ambiente social com outros iniciantes.',
                            'preco' => 80.00,
                            'icone' => 'fa-users',
                            'features' => [
                                'Equipamento incluído',
                                '2 horas de aula',
                                'Máximo 6 pessoas',
                                'Ambiente social'
                            ],
                            'destaque' => false,
                            'imagem' => 'https://picsum.photos/500/300?random=2'
                        ],
                        [
                            'titulo' => 'Curso Completo',
                            'descricao' => 'Programa intensivo para levar você do iniciante ao intermediário.',
                            'preco' => 500.00,
                            'icone' => 'fa-trophy',
                            'features' => [
                                '8 aulas de 2 horas',
                                'Equipamento incluído',
                                'Material didático',
                                'Certificado oficial'
                            ],
                            'destaque' => true,
                            'imagem' => 'https://picsum.photos/500/300?random=3'
                        ]
                    ];
                @endphp
                @foreach($fakeServices as $servico)
                    <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg  relative">
                        @if($servico['destaque'])
                            <div class="absolute top-4 left-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold z-10">
                                Mais Popular
                            </div>
                        @endif
                        <div class="h-48 relative overflow-hidden">
                            <img src="{{ $servico['imagem'] }}" alt="{{ $servico['titulo'] }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute top-4 right-4 bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-full p-3">
                                <i class="fas {{ $servico['icone'] }} text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $servico['titulo'] }}</h3>
                            <p class="text-gray-600 mb-4">{{ $servico['descricao'] }}</p>
                            
                            @if(!empty($servico['features']) && is_array($servico['features']))
                                <ul class="mb-6 text-gray-600 space-y-2">
                                    @foreach($servico['features'] as $feature)
                                        <li class="flex items-center">
                                            <i class="fas fa-check text-green-500 mr-3"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                {{-- <span class="text-3xl font-bold service-icon">R$ {{ number_format($servico['preco'], 2, ',', '.') }}</span> --}}
                                <button class="btn-primary text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 hover:scale-105">
                                    Reservar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                @foreach($site->servicos as $servico)
                    <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg stagger-item relative">
                        @if($servico->destaque)
                            <div class="absolute top-4 left-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold z-10">
                                Mais Popular
                            </div>
                        @endif
                        <div class="h-48 relative overflow-hidden">
                            @if($servico->imagem)
                                <img src="{{ asset('storage/' . $servico->imagem) }}" alt="{{ $servico->titulo }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" alt="{{ $servico->titulo }}" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute top-4 right-4 bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-full p-3">
                                <i class="fas {{ $servico->icone ?? 'fa-water' }} text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $servico->titulo }}</h3>
                            <p class="text-gray-600 mb-4">{{ $servico->descricao }}</p>
                            
                            @if(!empty($servico->features) && is_array($servico->features))
                                <ul class="mb-6 text-gray-600 space-y-2">
                                    @foreach($servico->features as $feature)
                                        <li class="flex items-center">
                                            <i class="fas fa-check text-green-500 mr-3"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            
                            <div class="flex justify-between items-center">
                                <span class="text-3xl font-bold service-icon">R$ {{ number_format($servico->preco, 2, ',', '.') }}</span>
                                <button class="btn-primary text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 hover:scale-105">
                                    Reservar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white parallax-bg" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ $site->sobre_imagem ? asset('storage/' . $site->sobre_imagem) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80' }}');">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="about-content">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">{{ $site->sobre_titulo ?? 'Por que escolher a OceanWave?' }}</h2>
                    <p class="text-gray-200 text-lg mb-8">{{ $site->sobre_descricao ?? 'Com mais de 15 anos de experiência, somos a escola de surf líder na região.' }}</p>
                    
                    <div class="space-y-6 about-features">
                        @foreach($site->sobre_itens ?? [
                            ['icone' => 'fa-award', 'titulo' => 'Instrutores Certificados', 'descricao' => 'Todos nossos instrutores possuem certificação internacional e anos de experiência.'],
                            ['icone' => 'fa-leaf', 'titulo' => 'Sustentabilidade', 'descricao' => 'Comprometidos com a preservação do meio ambiente e oceanos limpos.'],
                            ['icone' => 'fa-heart', 'titulo' => 'Comunidade', 'descricao' => 'Mais que uma escola, somos uma família de amantes do surf.']
                        ] as $item)
                            <div class="flex items-start glass-card p-4 rounded-lg">
                                <div class="bg-blue-500 p-3 rounded-full mr-4 flex-shrink-0">
                                    <i class="fas {{ $item['icone'] }} text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-white mb-2">{{ $item['titulo'] }}</h3>
                                    <p class="text-gray-200">{{ $item['descricao'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="about-image">
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($site->sobre_imagens ?? [
                            'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                            'https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                            'https://images.unsplash.com/photo-1530549387789-4c1017266635?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
                            'https://images.unsplash.com/photo-1537519646099-335112b4e681?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
                        ] as $imagem)
                            <img src="{{ $imagem }}" alt="Imagem sobre" class="rounded-lg shadow-lg">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
 <section id="testimonials" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 section-header">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">{{ $site->depoimentos_titulo ?? 'O Que Dizem Nossos Alunos' }}</h2>
            <div class="section-divider max-w-md mx-auto"></div>
            <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                {{ $site->depoimentos_descricao ?? 'Histórias reais de transformação e conquistas' }}
            </p>
        </div>
    
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 testimonials-grid">
        @if(!isset($site->depoimentos) || $site->depoimentos->isEmpty())
                @php
                                    $fakeTestimonials = [
                        [
                            'nome' => 'Ana Silva',
                            'comentario' => 'Uma experiência incrível! Os instrutores são pacientes e o ambiente é super acolhedor.',
                            'nota' => 5,
                            'servico' => 'Aula Individual - 2023',
                            'foto' => 'https://picsum.photos/50/50?random=1'
                        ],
                        [
                            'nome' => 'João Mendes',
                            'comentario' => 'Aprendi a surfar em poucas aulas! Recomendo a todos que querem uma aventura única.',
                            'nota' => 4.5,
                            'servico' => 'Curso Completo - 2023',
                            'foto' => 'https://picsum.photos/50/50?random=2'
                        ],
                        [
                            'nome' => 'Mariana Costa',
                            'comentario' => 'A melhor escola de surf! Me senti segura e aprendi muito com a equipe.',
                            'nota' => 5,
                            'servico' => 'Aula em Grupo - 2023',
                            'foto' => 'https://picsum.photos/50/50?random=3'
                        ]
                    ];
          
                @endphp
                @foreach($fakeTestimonials as $depoimento)           
                    <div class="testimonial-card p-6 rounded-2xl shadow-lg stagger-item-depoimento">
                        <div class="flex items-center mb-4">
                            <img src="{{ $depoimento['foto'] }}" alt="{{ $depoimento['nome'] }}" class="w-12 h-12 rounded-full mr-4">
                            <div>ssss
                                <h4 class="font-semibold text-gray-800">{{ $depoimento['nome'] }}</h4>
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $depoimento['nota'])
                                            <i class="fas fa-star"></i>
                                        @elseif($i - $depoimento['nota'] === 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"{{ $depoimento['comentario'] }}"</p>
                        <div class="mt-4 text-sm text-gray-500">
                            <i class="fas fa-quote-left mr-2"></i>
                            {{ $depoimento['servico'] }}
                        </div>
                    </div>
                @endforeach
            @else
                @foreach($site->depoimentos as $depoimento)
                    <div class="testimonial-card p-6 rounded-2xl shadow-lg stagger-item">
                        <div class="flex items-center mb-4">                         
                            <img src="{{ $depoimento->foto ? asset('storage/' . $depoimento->foto) : 'https://picsum.photos/50/50?random=' . rand(1, 100) }}" alt="{{ $depoimento->nome }}" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $depoimento->nome }}</h4>
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $depoimento->nota)
                                            <i class="fas fa-star"></i>
                                        @elseif($i - $depoimento->nota === 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"{{ $depoimento->comentario }}"</p>
                        <div class="mt-4 text-sm text-gray-500">
                            <i class="fas fa-quote-left mr-2"></i>
                            {{ $depoimento->servico ?? 'Curso 2023' }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

    <!-- CTA Section -->
    <section class="py-20 hero-gradient text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 text-glow cta-title">{{ $site->cta_titulo ?? 'Pronto para Pegar Sua Primeira Onda?' }}</h2>
            <p class="text-xl md:text-2xl mb-8 opacity-90 cta-subtitle">{{ $site->cta_descricao ?? 'Junte-se a centenas de alunos que já transformaram suas vidas através do surf' }}</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 cta-buttons">
                <button class="bg-white text-gray-800 hover:bg-gray-100 px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all duration-300">
                    <i class="fas fa-phone mr-2"></i>
                    {{ $site->whatsapp  ?? '(11) 99999-9999' }}
                </button>
                <button class="border-2 border-white text-white hover:bg-white hover:text-gray-800 px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300">
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
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">{{ $site->contato_titulo ?? 'Fale Conosco' }}</h2>
                <div class="section-divider max-w-md mx-auto"></div>
                <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                    {{ $site->contato_descricao ?? 'Estamos aqui para tirar suas dúvidas e ajudar você a começar sua jornada no surf' }}
                </p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Contact Info -->
                    <div class="contact-info">
                        <div class="glass-card p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-purple-50">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Informações de Contato</h3>
                            
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="bg-blue-500 p-3 rounded-full mr-4">
                                        <i class="fas fa-map-marker-alt text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Endereço</h4>
                                        <p class="text-gray-600">{{ $site->endereco ?? 'Av. Beira Mar, 1234<br>Praia do Sol - SP, 11000-000' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="bg-green-500 p-3 rounded-full mr-4">
                                        <i class="fas fa-phone text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Telefone</h4>
                                        <p class="text-gray-600">{{ $site->whatsapp ?? '(11) 99999-9999' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="bg-purple-500 p-3 rounded-full mr-4">
                                        <i class="fas fa-envelope text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Email</h4>
                                        <p class="text-gray-600">{{ $site->email ?? 'contato@oceanwave.com.br' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="bg-orange-500 p-3 rounded-full mr-4">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Horário</h4>
                                        <p class="text-gray-600">{{ $site->horario ?? 'Seg - Sáb: 7h às 18h<br>Dom: 8h às 16h' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="contact-form">
                        <form class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Nome</label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Telefone</label>
                                    <input type="tel" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Serviço de Interesse</label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                    @foreach($site->servicos as $servico)
                                        <option>{{ $servico->titulo }}</option>
                                    @endforeach
                                    <option>Informações Gerais</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Mensagem</label>
                                <textarea rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300" placeholder="Conte-nos mais sobre seu interesse..."></textarea>
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

    <!-- Footer -->
    <footer class="hero-gradient text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-water text-2xl service-icon"></i>
                        </div>
                        <span class="text-2xl font-bold">{{ $site->titulo }}</span>
                    </div>
                    <p class="mb-4 opacity-90">{{ $site->footer_descricao ?? 'Transformando vidas através do surf há mais de 15 anos.' }}</p>
                    <div class="flex space-x-4">
                        @foreach($site->redes_sociais ?? [
                            ['icone' => 'fa-facebook-f', 'url' => '#'],
                            ['icone' => 'fa-instagram', 'url' => '#'],
                            ['icone' => 'fa-youtube', 'url' => '#'],
                            ['icone' => 'fa-tiktok', 'url' => '#']
                        ] as $rede)
                            <a href="{{ $rede['url'] }}" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-all duration-300">
                                <i class="fab {{ $rede['icone'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Serviços</h3>
                    <ul class="space-y-2 opacity-90">
                        @foreach($site->servicos as $servico)
                            <li><a href="#services" class="hover:text-gray-200 transition-colors">{{ $servico->titulo }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links Úteis</h3>
                    <ul class="space-y-2 opacity-90">
                        <li><a href="#about" class="hover:text-gray-200 transition-colors">Sobre Nós</a></li>
                        <li><a href="#testimonials" class="hover:text-gray-200 transition-colors">Depoimentos</a></li>
                        <li><a href="#contact" class="hover:text-gray-200 transition-colors">Contato</a></li>
                        <li><a href="#" class="hover:text-gray-200 transition-colors">Blog</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="mb-4 opacity-90">Receba dicas, promoções e novidades!</p>
                    <div class="flex">
                        <input type="email" placeholder="Seu email" class="px-4 py-2 rounded-l-lg focus:outline-none text-gray-800 flex-1">
                        <button class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-r-lg transition-all duration-300">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-white border-opacity-20 mt-8 pt-8 text-center opacity-90">
                <p>&copy; {{ now()->year }} {{ $site->titulo }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>

        
        // Register GSAP plugins
        gsap.registerPlugin(ScrollTrigger, TextPlugin);

        // Loading screen animation
        window.addEventListener('load', function() {
            gsap.to('#loading', {
                opacity: 0,
                duration: 1,
                ease: "power2.out",
                onComplete: function() {
                    document.getElementById('loading').style.display = 'none';
                    initAnimations();
                }
            });
        });

        function initAnimations() {
            // Hero section animations
            gsap.timeline()
                .from('.hero-content h1', {
                    opacity: 0,
                    y: 100,
                    duration: 1,
                    ease: "power3.out"
                })
                .from('.hero-subtitle', {
                    opacity: 0,
                    y: 50,
                    duration: 0.8,
                    ease: "power2.out"
                }, "-=0.5")
                .from('.hero-buttons button', {
                    opacity: 0,
                    y: 30,
                    duration: 0.6,
                    stagger: 0.2,
                    ease: "back.out(1.7)"
                }, "-=0.3")
                .from('.hero-image', {
                    opacity: 0,
                    x: 100,
                    duration: 1,
                    ease: "power2.out"
                }, "-=1");

            // Typing effect for hero title
            const words = ["Ondas", "Mar", "Vida"];
            let currentWord = 0;
            
            function typeWord() {
                gsap.to('.typing-text', {
                    duration: 2,
                    text: words[currentWord],
                    ease: "none",
                    onComplete: function() {
                        setTimeout(() => {
                            currentWord = (currentWord + 1) % words.length;
                            typeWord();
                        }, 2000);
                    }
                });
            }
            typeWord();

            // Navbar scroll effect
            gsap.to('#navbar', {
                scrollTrigger: {
                    trigger: '#navbar',
                    start: 'top top',
                    end: '+=100',
                    scrub: true
                },
                backgroundColor: 'rgba(102, 126, 234, 0.95)',
                backdropFilter: 'blur(10px)',
                ease: "power2.out"
            });

            // Counter animation
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                
                gsap.fromTo(counter, {
                    innerHTML: 0
                }, {
                    innerHTML: target,
                    duration: 2,
                    ease: "power2.out",
                    snap: { innerHTML: 1 },
                    scrollTrigger: {
                        trigger: counter,
                        start: 'top 80%'
                    },
                    onUpdate: function() {
                        counter.innerHTML = Math.ceil(counter.innerHTML);
                    }
                });
            });

            // Stagger animations for cards and sections
            gsap.utils.toArray('.stagger-item').forEach((item, index) => {
                gsap.from(item, {
                    opacity: 0,
                    y: 100,
                    duration: 0.8,
                    delay: index * 0.2,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: item,
                        start: 'top 85%'
                    }
                });
            });

            // Section headers animation
            gsap.utils.toArray('.section-header').forEach(header => {
                gsap.from(header.children, {
                    opacity: 0,
                    y: 50,
                    duration: 1,
                    stagger: 0.2,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: header,
                        start: 'top 80%'
                    }
                });
            });

            // About section features animation
            gsap.utils.toArray('.about-features > div').forEach((feature, index) => {
                gsap.from(feature, {
                    opacity: 0,
                    x: -100,
                    duration: 0.8,
                    delay: index * 0.2,
                    ease: "back.out(1.7)",
                    scrollTrigger: {
                        trigger: feature,
                        start: 'top 85%'
                    }
                });
            });

            // CTA section animation
            gsap.from('.cta-title', {
                opacity: 0,
                scale: 0.5,
                duration: 1,
                ease: "back.out(1.7)",
                scrollTrigger: {
                    trigger: '.cta-title',
                    start: 'top 80%'
                }
            });

            gsap.from('.cta-subtitle', {
                opacity: 0,
                y: 30,
                duration: 0.8,
                delay: 0.3,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: '.cta-subtitle',
                    start: 'top 85%'
                }
            });

            gsap.from('.cta-buttons button', {
                opacity: 0,
                y: 30,
                duration: 0.6,
                stagger: 0.2,
                delay: 0.5,
                ease: "back.out(1.7)",
                scrollTrigger: {
                    trigger: '.cta-buttons',
                    start: 'top 85%'
                }
            });

            // Parallax effect for about section
            gsap.to('#about', {
                backgroundPosition: '50% 100%',
                ease: 'none',
                scrollTrigger: {
                    trigger: '#about',
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true
                }
            });
        }

        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

        mobileMenuBtn.addEventListener('click', () => {
            gsap.to(mobileMenu, {
                x: 0,
                duration: 0.3,
                ease: "power2.out"
            });
        });

        closeMobileMenu.addEventListener('click', () => {
            gsap.to(mobileMenu, {
                x: '-100%',
                duration: 0.3,
                ease: "power2.out"
            });
        });

        mobileNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                gsap.to(mobileMenu, {
                    x: '-100%',
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });

        // Smooth scrolling for all anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                if(this.getAttribute('href') === '#') return;
                
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    gsap.to(window, {
                        duration: 1,
                        scrollTo: {
                            y: target,
                            offsetY: 80
                        },
                        ease: "power2.out"
                    });
                }
            });
        });

        // Add hover animations to service cards
        document.querySelectorAll('.card-hover').forEach(card => {
            card.addEventListener('mouseenter', () => {
                gsap.to(card, {
                    y: -15,
                    scale: 1.02,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    y: 0,
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });

        // Form submission animation
        document.querySelector('.contact-form form').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const button = e.target.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Enviado!';
                button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.style.background = '';
                    e.target.reset();
                }, 2000);
            }, 2000);
        });
    </script>
</body>
</html>