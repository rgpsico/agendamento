<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fight Club Academy - Transforme-se Através do Boxe</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #0a0a0a;
            color: #fff;
            overflow-x: hidden;
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
            justify-content: center;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Hero Section */
        .hero-gradient {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #ff4500 100%);
            position: relative;
        }

        .hero {
            height: 100vh;
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
            background: radial-gradient(ellipse at center, transparent 0%, rgba(0,0,0,0.3) 100%);
            z-index: 1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-content h1 {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: bold;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            text-transform: uppercase;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            line-height: 1.6;
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

        /* Wave Animation */
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

        /* Floating Animation */
        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Carousel Styles */
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

        .carousel-slide.prev {
            transform: translateX(-100%);
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        /* Carousel Navigation */
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

        .carousel-dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Scroll Indicator */
        .scroll-indicator {
            z-index: 10;
        }

        /* Typing Animation */
        .typing-text {
            border-right: 3px solid white;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { border-color: white; }
            51%, 100% { border-color: transparent; }
        }

        .cta-button {
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            color: white;
            padding: 1.2rem 3rem;
            font-size: 1.2rem;
            font-weight: bold;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 107, 53, 0.4);
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        /* Features Section */
        .features {
            padding: 6rem 2rem;
            background: #111;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 3rem;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-transform: uppercase;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .feature-card {
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            border: 1px solid #333;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 107, 53, 0.2);
        }

        .feature-icon {
            font-size: 3rem;
            color: #ff6b35;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #f7931e;
        }

        /* Stats Section */
        .stats {
            padding: 6rem 2rem;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .stat-item {
            color: white;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-label {
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* CTA Section */
        .cta-section {
            padding: 8rem 2rem;
            background: #0a0a0a;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, #ff6b35, #f7931e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-section p {
            font-size: 1.3rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background: #25d366;
            color: white;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 15px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }

        .whatsapp-float:hover {
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

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 24px;
                bottom: 20px;
                right: 20px;
            }

            .carousel-wrapper {
                height: 300px;
            }

            .hero-content p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-primary, .hero-buttons a {
                padding: 1rem 2rem;
                font-size: 1rem;
                text-align: center;
            }
        }

        @media (max-width: 640px) {
            .hero {
                padding: 2rem 0;
            }

            .container {
                padding: 0 1rem;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .hero-image {
                order: -1;
            }

            .carousel-wrapper {
                height: 250px;
            }
        }

        /* Particles Animation */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            background: #ff6b35;
            border-radius: 50%;
            opacity: 0.5;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header">
        <div class="nav-container">
            <div class="logo">Fight Club Academy</div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero hero-gradient text-white min-h-screen flex items-center relative overflow-hidden">
        <div class="wave-animation"></div>
        <div class="particles" id="particles"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="hero-content">
                    <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-6 text-glow">
                        <span class="typing-text" id="hero-title">Desperte o Lutador que Existe em Você</span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 opacity-90 hero-subtitle" id="hero-text">
                        Transforme sua vida através do boxe. Treine com os melhores, desenvolva disciplina, força e confiança. Sua jornada de transformação começa agora!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 hero-buttons">
                        <a href="#cta" class="btn-primary text-white px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all duration-300" id="hero-cta">
                            <i class="fas fa-fist-raised mr-2"></i>
                            Comece Sua Transformação
                        </a>
                        <a href="#features" class="border-2 border-white text-white hover:bg-white hover:text-gray-800 px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300">
                            <i class="fas fa-play mr-2"></i>
                            Ver Mais
                        </a>
                    </div>
                </div>
                
                <!-- Image Carousel -->
                <div class="hero-image relative">
                    <div class="floating carousel-container">
                        <div class="carousel-wrapper" id="carousel">
                            <div class="carousel-slide active">
                                <img src="https://images.unsplash.com/photo-1549719386-74dfcbf7dbed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                     alt="Boxeador Treinando" class="rounded-2xl shadow-2xl w-full h-96 object-cover">
                            </div>
                            <div class="carousel-slide">
                                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                     alt="Academia de Boxe" class="rounded-2xl shadow-2xl w-full h-96 object-cover">
                            </div>
                            <div class="carousel-slide">
                                <img src="https://images.unsplash.com/photo-1517438984742-1262db08379e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                     alt="Treino Intenso" class="rounded-2xl shadow-2xl w-full h-96 object-cover">
                            </div>
                            <div class="carousel-slide">
                                <img src="https://images.unsplash.com/photo-1544551763-77ef2d0cfc6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                     alt="Boxeador Profissional" class="rounded-2xl shadow-2xl w-full h-96 object-cover">
                            </div>
                        </div>
                        
                        <!-- Carousel Navigation -->
                        <div class="carousel-nav">
                            <button class="carousel-dot active" onclick="goToSlide(0)"></button>
                            <button class="carousel-dot" onclick="goToSlide(1)"></button>
                            <button class="carousel-dot" onclick="goToSlide(2)"></button>
                            <button class="carousel-dot" onclick="goToSlide(3)"></button>
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

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Por Que Escolher o Boxe?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-fist-raised feature-icon"></i>
                    <h3>Força e Resistência</h3>
                    <p>Desenvolva força explosiva e resistência cardiovascular através de treinos intensivos e progressivos.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-brain feature-icon"></i>
                    <h3>Disciplina Mental</h3>
                    <p>Fortaleça sua mente, desenvolva foco e aprenda a superar desafios dentro e fora do ringue.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shield-alt feature-icon"></i>
                    <h3>Autodefesa</h3>
                    <p>Aprenda técnicas eficazes de autodefesa e ganhe confiança para enfrentar qualquer situação.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-fire feature-icon"></i>
                    <h3>Queima de Calorias</h3>
                    <p>Elimine até 800 calorias por treino e conquiste o corpo dos seus sonhos de forma divertida.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users feature-icon"></i>
                    <h3>Comunidade</h3>
                    <p>Faça parte de uma família de lutadores que se apoiam mutuamente na jornada de crescimento.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-trophy feature-icon"></i>
                    <h3>Conquiste Objetivos</h3>
                    <p>Supere seus limites e alcance metas que você nunca imaginou ser possível atingir.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <h2 class="section-title" style="color: white;">Resultados Comprovados</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number" id="stat1">500</span>
                    <span class="stat-label">Alunos Transformados</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="stat2">95</span>
                    <span class="stat-label">% de Satisfação</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="stat3">5</span>
                    <span class="stat-label">Anos de Experiência</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="stat4">50</span>
                    <span class="stat-label">Campeões Formados</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="cta">
        <div class="container">
            <h2>Pronto Para Começar?</h2>
            <p>Não deixe para amanhã a transformação que você pode começar hoje. Junte-se aos nossos alunos e descubra o poder do boxe!</p>
            <a href="#" class="cta-button" onclick="openWhatsApp()">Quero Começar Agora</a>
        </div>
    </section>

    <!-- WhatsApp Floating Button -->
    <a href="#" class="whatsapp-float" onclick="openWhatsApp()" title="Entre em contato pelo WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script>
        // Variáveis do carrossel
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        const totalSlides = slides.length;

        // Função para ir para um slide específico
        function goToSlide(slideIndex) {
            // Remove classes ativas
            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');
            
            // Atualiza o slide atual
            currentSlide = slideIndex;
            
            // Adiciona classes ativas
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        // Função para próximo slide
        function nextSlide() {
            const nextIndex = (currentSlide + 1) % totalSlides;
            goToSlide(nextIndex);
        }

        // Auto-play do carrossel
        function startCarousel() {
            setInterval(nextSlide, 4000); // Muda a cada 4 segundos
        }

        // Inicializar carrossel após carregamento
        document.addEventListener('DOMContentLoaded', function() {
            startCarousel();
        });

        // Animação de digitação
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
                    // Remove cursor após terminar
                    setTimeout(() => {
                        element.style.borderRight = 'none';
                    }, 1000);
                }
            }
            type();
        }

        // Registrar ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // Animações de entrada da hero section
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

        // Inicializar animação de digitação
        window.addEventListener('load', function() {
            const titleElement = document.getElementById('hero-title');
            const originalText = titleElement.textContent;
            typeWriter(titleElement, originalText, 80);
        });

        // Animação das partículas
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 20;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 4 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
                
                particlesContainer.appendChild(particle);
            }
        }

        createParticles();

        // Animação dos cards de features
        gsap.from(".feature-card", {
            duration: 0.8,
            y: 100,
            opacity: 0,
            stagger: 0.2,
            ease: "power2.out",
            scrollTrigger: {
                trigger: ".features-grid",
                start: "top 80%"
            }
        });

        // Animação dos números das estatísticas
        function animateStats() {
            const stats = [
                { id: "stat1", target: 500 },
                { id: "stat2", target: 95 },
                { id: "stat3", target: 5 },
                { id: "stat4", target: 50 }
            ];

            stats.forEach(stat => {
                gsap.to(`#${stat.id}`, {
                    duration: 2,
                    innerText: stat.target,
                    ease: "power2.out",
                    snap: { innerText: 1 },
                    scrollTrigger: {
                        trigger: ".stats",
                        start: "top 80%"
                    }
                });
            });
        }

        animateStats();

        // Animação da seção CTA
        gsap.from(".cta-section h2", {
            duration: 1,
            y: 50,
            opacity: 0,
            scrollTrigger: {
                trigger: ".cta-section",
                start: "top 80%"
            }
        });

        gsap.from(".cta-section p", {
            duration: 1,
            y: 30,
            opacity: 0,
            delay: 0.2,
            scrollTrigger: {
                trigger: ".cta-section",
                start: "top 80%"
            }
        });

        gsap.from(".cta-section .cta-button", {
            duration: 0.8,
            scale: 0,
            opacity: 0,
            delay: 0.5,
            ease: "back.out(1.7)",
            scrollTrigger: {
                trigger: ".cta-section",
                start: "top 80%"
            }
        });

        // Header transparente no scroll
        gsap.to("#header", {
            backgroundColor: "rgba(0,0,0,0.95)",
            backdropFilter: "blur(10px)",
            scrollTrigger: {
                trigger: "body",
                start: "100px top",
                toggleActions: "play none none reverse"
            }
        });

        // Função para abrir WhatsApp
        function openWhatsApp() {
            const phoneNumber = "5511999999999"; // Substitua pelo seu número
            const message = encodeURIComponent("Olá! Gostaria de saber mais sobre os treinos de boxe!");
            window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
        }

        // Animação smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    gsap.to(window, {
                        duration: 1,
                        scrollTo: target,
                        ease: "power2.inOut"
                    });
                }
            });
        });

        // Animação do botão WhatsApp flutuante
        gsap.from(".whatsapp-float", {
            duration: 1,
            scale: 0,
            opacity: 0,
            delay: 2,
            ease: "back.out(1.7)"
        });
    </script>
</body>
</html>