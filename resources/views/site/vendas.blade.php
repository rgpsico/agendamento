<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RJ Passeios - Sistema de Agendamento para Serviços de Verão</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="300" cy="700" r="80" fill="url(%23a)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: slideInLeft 1s ease-out;
        }

        .hero-text p {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
            animation: slideInLeft 1s ease-out 0.2s both;
        }

        .cta-button {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 18px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
            animation: slideInLeft 1s ease-out 0.4s both;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 107, 0.4);
            background: linear-gradient(45deg, #ee5a24, #ff6b6b);
        }

        .hero-visual {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            animation: slideInRight 1s ease-out;
        }

        .dashboard-preview {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .dashboard-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f1f1;
        }

        .dashboard-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            border-left: 4px solid #667eea;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .features {
            padding: 100px 0;
            background: linear-gradient(to bottom, #f8f9ff, white);
        }

        .section-title {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
        }

        .feature-icon i {
            font-size: 2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .feature-description {
            color: #666;
            line-height: 1.6;
        }

        .benefits {
            padding: 100px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .benefits-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .benefits-list {
            list-style: none;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            opacity: 0;
            transform: translateX(-30px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .benefit-item:nth-child(1) { animation-delay: 0.1s; }
        .benefit-item:nth-child(2) { animation-delay: 0.2s; }
        .benefit-item:nth-child(3) { animation-delay: 0.3s; }
        .benefit-item:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .benefit-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .benefit-text h3 {
            font-size: 1.3rem;
            margin-bottom: 8px;
        }

        .benefit-text p {
            color: rgba(255,255,255,0.9);
            line-height: 1.5;
        }

        .integration-visual {
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
        }

        .integration-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .integration-card {
            background: rgba(255,255,255,0.15);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            backdrop-filter: blur(5px);
        }

        .integration-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .cta-section {
            padding: 100px 0;
            background: linear-gradient(to right, #f8f9ff, #e8f0ff);
            text-align: center;
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-section h2 {
            font-size: 3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 40px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 18px 35px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background: transparent;
            color: #667eea;
            padding: 18px 35px;
            border: 2px solid #667eea;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover, .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .benefits-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-stats {
                grid-template-columns: 1fr;
            }

            .integration-grid {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .floating-icon {
            position: absolute;
            color: rgba(102, 126, 234, 0.1);
            animation: floatAround 15s infinite linear;
        }

        .floating-icon:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-icon:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: -5s;
        }

        .floating-icon:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: -10s;
        }

        @keyframes floatAround {
            0% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(120deg); }
            66% { transform: translateY(15px) rotate(240deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <i class="fas fa-calendar-alt floating-icon" style="font-size: 4rem;"></i>
        <i class="fas fa-users floating-icon" style="font-size: 3rem;"></i>
        <i class="fas fa-chart-line floating-icon" style="font-size: 3.5rem;"></i>
    </div>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>RJ Passeios</h1>
                    <p>O sistema completo para gerenciar seus serviços de verão. Aulas de surf, passeios e muito mais, tudo em uma plataforma intuitiva e poderosa.</p>
                    <a href="#cta" class="cta-button">
                        <i class="fas fa-rocket"></i> Começar Agora
                    </a>
                </div>
                <div class="hero-visual">
                    <div class="dashboard-preview">
                        <div class="dashboard-header">
                            <div class="dashboard-icon">
                                <i class="fas fa-tachometer-alt" style="color: white;"></i>
                            </div>
                            <div>
                                <h3>Dashboard RJ Passeios</h3>
                                <p style="color: #666; margin: 0;">Visão geral do seu negócio</p>
                            </div>
                        </div>
                        <div class="dashboard-stats">
                            <div class="stat-card">
                                <div class="stat-number">152</div>
                                <div class="stat-label">Alunos Ativos</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">R$ 28.5k</div>
                                <div class="stat-label">Arrecadação</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">89</div>
                                <div class="stat-label">Aulas Hoje</div>
                            </div>
                        </div>
                        <div style="height: 80px; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); border-radius: 10px; position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 50%; left: 20px; transform: translateY(-50%); color: white;">
                                <i class="fas fa-chart-area" style="font-size: 1.5rem;"></i>
                            </div>
                            <div style="position: absolute; top: 50%; right: 20px; transform: translateY(-50%); color: white; text-align: right;">
                                <div style="font-size: 0.9rem; opacity: 0.8;">Crescimento Mensal</div>
                                <div style="font-size: 1.4rem; font-weight: bold;">+24%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2 class="section-title">Funcionalidades Poderosas</h2>
            <p class="section-subtitle">Tudo que você precisa para gerenciar seu negócio de serviços de verão com eficiência e profissionalismo</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Gestão Inteligente de Horários</h3>
                    <p class="feature-description">Configure horários únicos, personalizados ou automáticos. Adapte-se a diferentes modelos de agendamento com flexibilidade total para otimizar seus recursos.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Controle Completo de Alunos</h3>
                    <p class="feature-description">Cadastre e gerencie informações detalhadas de seus clientes. Acompanhamento individualizado para construir relacionamentos duradouros.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="feature-title">Múltiplas Opções de Pagamento</h3>
                    <p class="feature-description">Integração com Asaas, PIX, Stripe e Mercado Pago. Ofereça conveniência aos clientes e flexibilidade para suas transações financeiras.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3 class="feature-title">Interface Intuitiva</h3>
                    <p class="feature-description">Design limpo e navegação clara que reduz a curva de aprendizado. Menu organizado com acesso rápido a todas as funcionalidades.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="feature-title">Gestão Abrangente de Serviços</h3>
                    <p class="feature-description">Cadastre diferentes tipos de serviços com título, descrição, preço e duração. Flexibilidade para adaptar sua oferta às necessidades do negócio.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="feature-title">Dashboard Completo</h3>
                    <p class="feature-description">Visão geral instantânea do status do seu negócio com informações sobre alunos, arrecadação e aulas em uma interface organizada.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="benefits">
        <div class="container">
            <div class="benefits-content">
                <div>
                    <h2 class="section-title" style="color: white; text-align: left;">Por que escolher o RJ Passeios?</h2>
                    <ul class="benefits-list">
                        <li class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div class="benefit-text">
                                <h3>Otimização de Recursos</h3>
                                <p>Gerencie horários de forma granular para maximizar a utilização dos seus recursos e evitar conflitos de agendamento.</p>
                            </div>
                        </li>
                        <li class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="benefit-text">
                                <h3>Relacionamento com Clientes</h3>
                                <p>Mantenha um controle preciso sobre seus alunos e agendamentos, construindo relacionamentos duradouros.</p>
                            </div>
                        </li>
                        <li class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="benefit-text">
                                <h3>Segurança e Confiabilidade</h3>
                                <p>Plataforma robusta com integração segura de pagamentos e gestão centralizada de dados cadastrais.</p>
                            </div>
                        </li>
                        <li class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-trending-up"></i>
                            </div>
                            <div class="benefit-text">
                                <h3>Crescimento do Negócio</h3>
                                <p>Ferramentas profissionais que permitem escalar seu negócio de serviços de verão com eficiência.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="integration-visual">
                    <h3 style="text-align: center; margin-bottom: 30px; font-size: 1.5rem;">Integrações Poderosas</h3>
                    <div class="integration-grid">
                        <div class="integration-card">
                            <i class="fab fa-cc-stripe"></i>
                            <h4>Stripe</h4>
                        </div>
                        <div class="integration-card">
                            <i class="fas fa-qrcode"></i>
                            <h4>PIX</h4>
                        </div>
                        <div class="integration-card">
                            <i class="fas fa-credit-card"></i>
                            <h4>Asaas</h4>
                        </div>
                        <div class="integration-card">
                            <i class="fas fa-shopping-cart"></i>
                            <h4>Mercado Pago</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section" id="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Pronto para Revolucionar seu Negócio?</h2>
                <p>Junte-se a centenas de empresas que já transformaram a gestão de seus serviços de verão com o RJ Passeios</p>
                <div class="cta-buttons">
                    <a href="#" class="btn-primary">
                        <i class="fas fa-rocket"></i> Começar Gratuitamente
                    </a>
                    <a href="#" class="btn-secondary">
                        <i class="fas fa-play"></i> Ver Demonstração
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Animação suave para os links de âncora
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

        // Animação de contador para os números do dashboard
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    
                    if (counter.textContent.includes('R$')) {
                        counter.textContent = `R$ ${(current / 1000).toFixed(1)}k`;
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 20);
            });
        }

        // Executar animação quando a página carregar
        window.addEventListener('load', () => {
            setTimeout(animateCounters, 1000);
        });

        // Efeito de parallax sutil
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            const heroHeight = hero.offsetHeight;
            
            if (scrolled < heroHeight) {
                hero.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>