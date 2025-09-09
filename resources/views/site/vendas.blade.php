<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RJ Passeios - O Sistema Definitivo para Maximizar Suas Vendas em Serviços de Verão</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
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
        }

        .hero-text p {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
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
            opacity: 1;
            visibility: visible;
        }

        /* Estado inicial para animações GSAP */
        .gsap-loaded .feature-card {
            opacity: 0;
            transform: translateY(50px);
        }

        /* Fallback caso GSAP não carregue */
        .feature-card.animate {
            opacity: 1;
            transform: translateY(0);
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

        .testimonials {
            padding: 100px 0;
            background: #f8f9ff;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
            font-style: italic;
        }

        .testimonial-card p {
            margin-bottom: 15px;
        }

        .testimonial-author {
            font-weight: bold;
            color: #667eea;
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
            opacity: 1;
            visibility: visible;
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
            opacity: 1;
            visibility: visible;
        }

        /* Estado inicial para animações GSAP */
        .gsap-loaded .btn-primary,
        .gsap-loaded .btn-secondary {
            opacity: 0;
            transform: translateY(20px);
        }

        .btn-primary:hover, .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        .floating-icon {
            position: absolute;
            color: rgba(102, 126, 234, 0.1);
            opacity: 0.5;
        }

        .floating-icon:nth-child(1) {
            top: 20%;
            left: 10%;
            font-size: 4rem;
        }

        .floating-icon:nth-child(2) {
            top: 60%;
            right: 15%;
            font-size: 3rem;
        }

        .floating-icon:nth-child(3) {
            bottom: 30%;
            left: 20%;
            font-size: 3.5rem;
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

            .features-grid, .testimonials-grid {
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

            .floating-icon {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <i class="fas fa-calendar-alt floating-icon"></i>
        <i class="fas fa-users floating-icon"></i>
        <i class="fas fa-chart-line floating-icon"></i>
    </div>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>RJ Passeios: Aumente Suas Vendas em Até 40% e Simplifique Sua Gestão</h1>
                    <p>Descubra o sistema completo que gerencia aulas de surf, passeios e mais, impulsionando seu negócio de verão com automação inteligente e integrações seguras. Milhares de empreendedores já viram resultados reais!</p>
                    <a href="#cta" class="cta-button">
                        <i class="fas fa-rocket"></i> Comece Seu Teste Grátis Agora - Sem Cartão Necessário!
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
                                <p style="color: #666; margin: 0;">Visão geral do seu negócio em tempo real</p>
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
            <h2 class="section-title">Funcionalidades que Impulsionam Seu Sucesso</h2>
            <p class="section-subtitle">Economize horas por semana e aumente sua receita com ferramentas projetadas para negócios como o seu. Veja por que RJ Passeios é a escolha número 1 para serviços de verão.</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="feature-title">Gestão Inteligente de Horários</h3>
                    <p class="feature-description">Evite conflitos e maximize reservas com agendamentos automáticos e personalizados. Clientes felizes = mais recomendações e receita!</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Controle Completo de Alunos</h3>
                    <p class="feature-description">Gerencie dados de clientes com facilidade e personalize experiências para fidelizá-los, aumentando retenção em até 30%.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="feature-title">Múltiplas Opções de Pagamento</h3>
                    <p class="feature-description">Integre Asaas, PIX, Stripe e Mercado Pago para pagamentos instantâneos e seguros, reduzindo abandonos e impulsionando vendas.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3 class="feature-title">Interface Intuitiva</h3>
                    <p class="feature-description">Comece a usar em minutos, sem treinamento caro. Design moderno que torna a gestão do seu negócio simples e eficiente.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="feature-title">Gestão Abrangente de Serviços</h3>
                    <p class="feature-description">Crie e personalize serviços ilimitados com preços dinâmicos, adaptando-se rapidamente ao mercado e crescendo seu portfólio.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 class="feature-title">Dashboard Completo</h3>
                    <p class="feature-description">Monitore métricas chave em tempo real e tome decisões baseadas em dados para otimizar e escalar seu negócio.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="benefits">
        <div class="container">
            <div class="benefits-content">
                <div>
                    <h2 class="section-title" style="color: white; text-align: left;">Por Que RJ Passeios Vai Transformar Seu Negócio?</h2>
                    <ul class="benefits-list">
                        <li class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div class="benefit-text">
                                <h3>Otimização de Recursos</h3>
                                <p>Maximize cada minuto e recurso, evitando perdas e aumentando eficiência em até 50% com agendamentos inteligentes.</p>
                            </div>
                        </li>
                        <li class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="benefit-text">
                                <h3>Relacionamento com Clientes</h3>
                                <p>Construa lealdade com acompanhamento personalizado, transformando clientes em fãs que voltam e recomendam.</p>
                            </div>
                        </li>
                        <li class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="benefit-text">
                                <h3>Segurança e Confiabilidade</h3>
                                <p>Proteja seus dados e transações com integrações seguras, dando paz de espírito para focar no crescimento.</p>
                            </div>
                        </li>
                        <li class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-trending-up"></i>
                            </div>
                            <div class="benefit-text">
                                <h3>Crescimento Acelerado</h3>
                                <p>Escolha ferramentas comprovadas que ajudaram centenas de negócios a dobrar sua receita em meses.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="integration-visual">
                    <h3 style="text-align: center; margin-bottom: 30px; font-size: 1.5rem;">Integrações Poderosas e Seguras</h3>
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

    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">Resultados Reais de Clientes Satisfeitos</h2>
            <p class="section-subtitle">Não acredite só na nossa palavra. Veja como RJ Passeios está revolucionando negócios como o seu.</p>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p>"RJ Passeios dobrou minhas reservas em apenas 3 meses! A integração com pagamentos é impecável."</p>
                    <span class="testimonial-author">- João Silva, Instrutor de Surf no Rio</span>
                </div>
                <div class="testimonial-card">
                    <p>"Economizei horas por dia gerenciando horários. Meu negócio cresceu 35% graças a essa ferramenta!"</p>
                    <span class="testimonial-author">- Maria Oliveira, Guia de Passeios Ecológicos</span>
                </div>
                <div class="testimonial-card">
                    <p>"Interface intuitiva e suporte incrível. Recomendo para qualquer empreendedor de serviços de verão."</p>
                    <span class="testimonial-author">- Pedro Santos, Escola de Kitesurf</span>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section" id="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Pronto para Explosão de Crescimento no Seu Negócio?</h2>
                <p>Junte-se a milhares de empreendedores que já aumentaram suas vendas e simplificaram operações com RJ Passeios. Oferta limitada: Teste grátis por 14 dias!</p>
                <div class="cta-buttons">
                    <a href="#" class="btn-primary">
                        <i class="fas fa-rocket"></i> Comece Gratuitamente Hoje
                    </a>
                    <a href="#" class="btn-secondary">
                        <i class="fas fa-play"></i> Ver Demonstração Gratuita
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if($site->atendimento_com_ia)
    <x-batepapo />
    @endif


       @if($site->atendimento_com_whatsapp)
            {{-- <x-atendimento-whatsapp :numero="$site->whatsapp" mensagem="Olá! Gostaria de atendimento." /> --}}
       @endif



    <script>
        // Verificar se GSAP carregou corretamente
        if (typeof gsap !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);
            
            // Marcar que GSAP carregou
            document.documentElement.classList.add('gsap-loaded');

            // Aguardar carregamento completo da página
            window.addEventListener('load', function() {
                // Hero animations com verificação de elementos
                const heroTimeline = gsap.timeline();
                
                if (document.querySelector('.hero-text h1')) {
                    heroTimeline
                        .from(".hero-text h1", { opacity: 0, x: -50, duration: 1 })
                        .from(".hero-text p", { opacity: 0, x: -50, duration: 0.8 }, "-=0.5")
                        .from(".cta-button", { opacity: 0, y: 20, duration: 0.8 }, "-=0.5")
                        .from(".hero-visual", { opacity: 0, x: 50, duration: 1 }, "-=1");
                }

                // Animações dos ícones flutuantes com verificações
                if (document.querySelector('.floating-icon')) {
                    gsap.to(".floating-icon:nth-child(1)", { 
                        y: -30, 
                        rotation: 120, 
                        duration: 8, 
                        repeat: -1, 
                        yoyo: true, 
                        ease: "sine.inOut" 
                    });
                    
                    gsap.to(".floating-icon:nth-child(2)", { 
                        y: 15, 
                        rotation: 240, 
                        duration: 10, 
                        repeat: -1, 
                        yoyo: true, 
                        ease: "sine.inOut", 
                        delay: 1 
                    });
                    
                    gsap.to(".floating-icon:nth-child(3)", { 
                        y: -15, 
                        rotation: -120, 
                        duration: 7, 
                        repeat: -1, 
                        yoyo: true, 
                        ease: "sine.inOut", 
                        delay: 2 
                    });
                }

                // Features animation
                if (document.querySelector('.features-grid')) {
                    gsap.fromTo(".feature-card", 
                        {
                            opacity: 0,
                            y: 50
                        },
                        {
                            opacity: 1,
                            y: 0,
                            duration: 0.8,
                            stagger: 0.15,
                            ease: "back.out(1.7)",
                            scrollTrigger: {
                                trigger: ".features-grid",
                                start: "top 85%",
                                toggleActions: "play none none none",
                                once: true
                            }
                        }
                    );
                }

                // Benefits animation
                if (document.querySelector('.benefits-list')) {
                    gsap.fromTo(".benefit-item", 
                        {
                            opacity: 0,
                            x: -30
                        },
                        {
                            opacity: 1,
                            x: 0,
                            duration: 0.6,
                            stagger: 0.1,
                            scrollTrigger: {
                                trigger: ".benefits-list",
                                start: "top 80%",
                                toggleActions: "play none none none",
                                once: true
                            }
                        }
                    );
                }

                // Testimonials animation
                if (document.querySelector('.testimonials-grid')) {
                    gsap.fromTo(".testimonial-card", 
                        {
                            opacity: 0,
                            y: 50
                        },
                        {
                            opacity: 1,
                            y: 0,
                            duration: 0.8,
                            stagger: 0.2,
                            scrollTrigger: {
                                trigger: ".testimonials-grid",
                                start: "top 80%",
                                toggleActions: "play none none none",
                                once: true
                            }
                        }
                    );
                }

                // CTA buttons animation
                if (document.querySelector('.cta-buttons')) {
                    gsap.fromTo(".btn-primary, .btn-secondary", 
                        {
                            opacity: 0,
                            y: 20
                        },
                        {
                            opacity: 1,
                            y: 0,
                            duration: 0.8,
                            stagger: 0.2,
                            ease: "back.out(1.7)",
                            scrollTrigger: {
                                trigger: ".cta-buttons",
                                start: "top 85%",
                                toggleActions: "play none none none",
                                once: true
                            }
                        }
                    );
                }

                // Parallax effect suave para o hero
                if (window.innerWidth > 768) { // Apenas em desktop
                    gsap.to(".hero", {
                        yPercent: -25,
                        ease: "none",
                        scrollTrigger: {
                            trigger: ".hero",
                            scrub: 1,
                            start: "top top",
                            end: "bottom top"
                        }
                    });
                }
            });

        } else {
            console.warn('GSAP não carregou. Aplicando fallbacks CSS.');
            // Fallback para quando GSAP não carregar
            document.addEventListener('DOMContentLoaded', function() {
                // Adicionar animações CSS como fallback
                const style = document.createElement('style');
                style.textContent = `
                    .hero-text h1, .hero-text p, .cta-button, .hero-visual {
                        animation: fadeInUp 1s ease-out forwards;
                    }
                    .hero-text p { animation-delay: 0.2s; }
                    .cta-button { animation-delay: 0.4s; }
                    .hero-visual { animation-delay: 0.6s; }
                    
                    .btn-primary, .btn-secondary {
                        animation: slideInUp 0.8s ease-out forwards;
                    }
                    
                    .btn-primary { animation-delay: 0.1s; }
                    .btn-secondary { animation-delay: 0.3s; }
                    
                    @keyframes fadeInUp {
                        from {
                            opacity: 0;
                            transform: translateY(30px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                    
                    @keyframes slideInUp {
                        from {
                            opacity: 0;
                            transform: translateY(50px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                `;
                document.head.appendChild(style);
                
                // Garantir que os elementos fiquem visíveis
                setTimeout(() => {
                    document.querySelectorAll('.feature-card').forEach(card => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                }, 100);
            });
        }

        // Intersection Observer melhorado para garantir visibilidade
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                        // Garantir visibilidade
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Aguardar carregamento e então observar
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    const elementsToAnimate = document.querySelectorAll('.feature-card, .benefit-item, .testimonial-card');
                    elementsToAnimate.forEach(el => {
                        // Garantir estado inicial visível caso as animações falhem
                        if (!document.documentElement.classList.contains('gsap-loaded')) {
                            el.style.opacity = '1';
                            el.style.transform = 'translateY(0)';
                        }
                        observer.observe(el);
                    });
                }, 500);
            });
        } else {
            // Fallback para navegadores sem Intersection Observer
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    document.querySelectorAll('.feature-card, .benefit-item, .testimonial-card').forEach(el => {
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    });
                }, 500);
            });
        }

        // Smooth scrolling para âncoras
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a[href^="#"]');
            
            links.forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    
                    if (target) {
                        const headerHeight = 0; // Ajuste se tiver header fixo
                        const targetPosition = target.offsetTop - headerHeight;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });

        // Performance: Throttle do scroll para animações
        let ticking = false;
        
        function updateAnimations() {
            // Animações baseadas em scroll podem ser adicionadas aqui
            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateAnimations);
                ticking = true;
            }
        }

        window.addEventListener('scroll', requestTick, { passive: true });

        // Intersection Observer para lazy loading de animações
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Observar elementos para animação
            const elementsToAnimate = document.querySelectorAll('.feature-card, .benefit-item, .testimonial-card');
            elementsToAnimate.forEach(el => observer.observe(el));
        }
    </script>
</body>
</html>