<x-admin.layout title="Editar Bot">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 2% 5%;">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Editar Bot"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('admin.bot.update', $bot->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <!-- Card Principal - Informações do Bot -->
                        <div class="card shadow-xl border-0 mb-4 bot-info-card" data-aos="fade-up">
                            <div class="card-header bg-gradient-primary text-white position-relative overflow-hidden">
                                <div class="header-bg-animation"></div>
                                <div class="d-flex align-items-center position-relative z-index-2">
                                    <div class="icon-container me-3">
                                        <i class="fas fa-robot robot-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-bold">Informações do Bot</h5>
                                </div>
                            </div>
                            
                            <input type="hidden" id="empresa_id" name="empresa_id" class="form-control" value="{{ $bot->empresa_id }}" required>
                            
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4 form-group-animated">
                                            <label for="nome" class="form-label fw-semibold floating-label">
                                                <i class="fas fa-tag text-primary me-1 label-icon"></i>
                                                Nome do Bot
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="text" id="nome" name="nome" class="form-control form-control-lg animated-input" 
                                                       value="{{ old('nome', $bot->nome) }}" required 
                                                       placeholder="Digite o nome do bot">
                                                <div class="input-underline"></div>
                                            </div>
                                            <div class="invalid-feedback">
                                                Por favor, informe o nome do bot.
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-4 form-group-animated">
                                            <label for="segmento" class="form-label fw-semibold floating-label">
                                                <i class="fas fa-industry text-primary me-1 label-icon"></i>
                                                Segmento
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="text" id="segmento" name="segmento" class="form-control form-control-lg animated-input" 
                                                       value="{{ old('segmento', $bot->segmento) }}" required
                                                       placeholder="Ex: E-commerce, Educação, Saúde">
                                                <div class="input-underline"></div>
                                            </div>
                                            <div class="invalid-feedback">
                                                Por favor, informe o segmento.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 form-group-animated">
                                    <label for="prompt" class="form-label fw-semibold floating-label">
                                        <i class="fas fa-brain text-primary me-1 label-icon"></i>
                                        Prompt / Missão do Bot
                                    </label>
                                    <div class="textarea-wrapper">
                                        <textarea id="prompt" name="prompt" class="form-control animated-textarea" rows="5" 
                                                  placeholder="Ex: Você é um assistente especializado em aulas de surf, sempre amigável e motivador. Seu objetivo é...">{{ old('prompt', $bot->prompt) }}</textarea>
                                        <div class="textarea-underline"></div>
                                    </div>
                                    <small class="text-muted animate-tip">
                                        <i class="fas fa-lightbulb me-1 tip-icon"></i>
                                        Defina a personalidade e objetivos do seu bot de forma clara e detalhada.
                                    </small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4 form-group-animated">
                                            <label for="tom" class="form-label fw-semibold floating-label">
                                                <i class="fas fa-comment-dots text-primary me-1 label-icon"></i>
                                                Tom do Bot
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="text" id="tom" name="tom" class="form-control form-control-lg animated-input" 
                                                       value="{{ old('tom', $bot->tom) }}"
                                                       placeholder="Ex: Amigável, Profissional, Casual">
                                                <div class="input-underline"></div>
                                            </div>
                                            <small class="text-muted">Como o bot deve se comunicar com os usuários</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-4 form-group-animated">
                                            <label for="token_deepseek" class="form-label fw-semibold floating-label">
                                                <i class="fas fa-key text-primary me-1 label-icon"></i>
                                                Token DeepSeek
                                            </label>
                                            <div class="input-group input-wrapper">
                                                <input type="password" id="token_deepseek" name="token_deepseek" 
                                                       class="form-control form-control-lg animated-input" 
                                                       value="{{ old('token_deepseek', $bot->token_deepseek) }}"
                                                       placeholder="Digite o token da API">
                                                <button class="btn btn-outline-secondary toggle-btn" type="button" id="toggleToken">
                                                    <i class="fas fa-eye toggle-icon" id="toggleIcon"></i>
                                                </button>
                                                <div class="input-underline"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch mb-3 switch-animated">
                                            <input type="checkbox" id="status" name="status" class="form-check-input animated-switch" 
                                                   value="1" {{ old('status', $bot->status) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold ms-2 switch-label" for="status">
                                                <i class="fas fa-power-off me-1 switch-icon"></i>
                                                <span class="switch-text">Bot Ativo</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Serviços -->
                        <div class="card shadow-xl border-0 mb-4 services-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header bg-gradient-info text-white position-relative overflow-hidden">
                                <div class="header-bg-animation-2"></div>
                                <div class="d-flex align-items-center position-relative z-index-2">
                                    <div class="icon-container me-3">
                                        <i class="fas fa-cogs services-icon"></i>
                                    </div>
                                    <h5 class="card-title mb-0 fw-bold">Serviços Gerenciados</h5>
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                <p class="text-muted mb-4 info-text">
                                    <i class="fas fa-info-circle me-2 info-icon"></i>
                                    Selecione quais serviços este bot será responsável por gerenciar
                                </p>
                                
                                <div class="row g-4 services-grid">
                                    @foreach($services as $index => $service)
                                        <div class="col-lg-3 col-md-4 col-sm-6 service-item" data-index="{{ $index }}">
                                            <div class="service-card p-4 border rounded-4 h-100 position-relative overflow-hidden">
                                                <div class="service-bg-effect"></div>
                                                <div class="form-check h-100 d-flex align-items-center position-relative z-index-2">
                                                    <input class="form-check-input me-3 service-checkbox" type="checkbox" 
                                                           name="services[]" value="{{ $service->id }}" 
                                                           id="service{{ $service->id }}"
                                                           {{ $bot->services->contains($service->id) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold text-truncate flex-grow-1 service-label" 
                                                           for="service{{ $service->id }}" title="{{ $service->titulo }}">
                                                        <i class="fas fa-puzzle-piece text-primary me-2 service-item-icon"></i>
                                                        {{ $service->titulo }}
                                                    </label>
                                                    <div class="check-animation"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if(count($services) == 0)
                                    <div class="text-center py-5 empty-state">
                                        <div class="empty-icon mb-3">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                        </div>
                                        <p class="text-muted fs-5">Nenhum serviço disponível para seleção.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Botões de Ação -->
                        <div class="card shadow-xl border-0 action-card" data-aos="fade-up" data-aos-delay="400">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="update-info">
                                        <small class="text-muted d-flex align-items-center">
                                            <i class="fas fa-clock me-2 clock-icon"></i>
                                            <span>Última atualização: {{ $bot->updated_at ? $bot->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </small>
                                    </div>
                                    <div class="btn-group action-buttons">
                                        <a href="{{ route('admin.bot.index') }}" class="btn btn-outline-secondary btn-lg px-5 cancel-btn">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            <span>Cancelar</span>
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg px-5 ms-3 save-btn">
                                            <i class="fas fa-save me-2 save-icon"></i>
                                            <span>Atualizar Bot</span>
                                            <div class="btn-ripple"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --info-gradient: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            --success-gradient: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            --shadow-light: 0 10px 30px rgba(0,0,0,0.1);
            --shadow-medium: 0 15px 40px rgba(0,0,0,0.15);
            --shadow-heavy: 0 25px 50px rgba(0,0,0,0.2);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Card Animations */
        .card {
            border-radius: 20px;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .bg-gradient-primary {
            background: var(--primary-gradient);
        }
        
        .bg-gradient-info {
            background: var(--info-gradient);
        }

        /* Header Animations */
        .header-bg-animation {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: headerFloat 6s ease-in-out infinite;
        }

        .header-bg-animation-2 {
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: headerFloat 8s ease-in-out infinite reverse;
        }

        @keyframes headerFloat {
            0%, 100% { transform: rotate(0deg) translateY(0px); }
            50% { transform: rotate(180deg) translateY(-20px); }
        }

        /* Icon Containers */
        .icon-container {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .robot-icon, .services-icon {
            font-size: 1.5rem;
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Form Animations */
        .form-group-animated {
            position: relative;
            overflow: hidden;
        }

        .input-wrapper, .textarea-wrapper {
            position: relative;
        }

        .animated-input, .animated-textarea {
            border: none;
            border-bottom: 2px solid #e9ecef;
            border-radius: 15px 15px 0 0;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .input-underline, .textarea-underline {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-gradient);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .animated-input:focus, .animated-textarea:focus {
            border-bottom-color: transparent;
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .animated-input:focus + .input-underline,
        .animated-textarea:focus + .textarea-underline {
            width: 100%;
        }

        .floating-label {
            transition: all 0.3s ease;
        }

        .label-icon {
            animation: labelIconFloat 3s ease-in-out infinite;
        }

        @keyframes labelIconFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-3px); }
        }

        /* Switch Animation */
        .switch-animated {
            position: relative;
        }

        .animated-switch {
            transform: scale(1.5);
            transition: all 0.3s ease;
        }

        .animated-switch:checked {
            animation: switchCheck 0.5s ease;
        }

        @keyframes switchCheck {
            0% { transform: scale(1.5); }
            50% { transform: scale(1.8); }
            100% { transform: scale(1.5); }
        }

        .switch-icon {
            transition: all 0.3s ease;
        }

        .switch-label:hover .switch-icon {
            animation: iconSpin 0.5s ease;
        }

        @keyframes iconSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Service Cards */
        .service-card {
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid #e9ecef !important;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
        }

        .service-bg-effect {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            opacity: 0;
            transition: all 0.5s ease;
            z-index: 0;
        }

        .service-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-heavy);
            border-color: #667eea !important;
        }

        .service-card:hover .service-bg-effect {
            left: 0;
            opacity: 0.1;
        }

        .service-checkbox {
            transform: scale(1.3);
            transition: all 0.3s ease;
        }

        .service-checkbox:checked {
            animation: checkboxBounce 0.6s ease;
        }

        @keyframes checkboxBounce {
            0%, 20%, 60%, 100% { transform: scale(1.3); }
            40% { transform: scale(1.6); }
            80% { transform: scale(1.4); }
        }

        .check-animation {
            position: absolute;
            top: 50%;
            right: 20px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--success-gradient);
            transform: translateY(-50%) scale(0);
            transition: all 0.3s ease;
        }

        .service-card:has(.service-checkbox:checked) {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(116, 75, 162, 0.1) 100%);
            border-color: #667eea !important;
            transform: translateY(-4px);
        }

        .service-card:has(.service-checkbox:checked) .check-animation {
            transform: translateY(-50%) scale(1);
        }

        /* Button Animations */
        .save-btn {
            background: var(--primary-gradient);
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-ripple {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: all 0.6s ease;
        }

        .save-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
        }

        .save-btn:hover .btn-ripple {
            width: 300px;
            height: 300px;
        }

        .save-icon {
            transition: all 0.3s ease;
        }

        .save-btn:hover .save-icon {
            animation: saveIconSpin 0.8s ease;
        }

        @keyframes saveIconSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .cancel-btn {
            transition: all 0.3s ease;
            border: 2px solid #6c757d;
        }

        .cancel-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-light);
            background: #6c757d;
            color: white;
        }

        /* Toggle Button */
        .toggle-btn {
            border: none;
            background: transparent;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            transform: scale(1.1);
        }

        .toggle-icon {
            transition: all 0.3s ease;
        }

        /* Tips and Info */
        .animate-tip {
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .tip-icon {
            animation: tipBlink 2s ease-in-out infinite;
        }

        @keyframes tipBlink {
            0%, 90%, 100% { opacity: 1; }
            95% { opacity: 0.5; }
        }

        .info-icon {
            animation: infoFloat 3s ease-in-out infinite;
        }

        @keyframes infoFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-2px) rotate(5deg); }
        }

        /* Clock Animation */
        .clock-icon {
            animation: clockTick 2s ease-in-out infinite;
        }

        @keyframes clockTick {
            0%, 50%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(6deg); }
            75% { transform: rotate(-6deg); }
        }

        /* Empty State */
        .empty-state {
            animation: emptyStateFade 2s ease-in-out infinite;
        }

        @keyframes emptyStateFade {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        .empty-icon i {
            font-size: 3rem;
            animation: emptyIconBounce 2s ease-in-out infinite;
        }

        @keyframes emptyIconBounce {
            0%, 20%, 60%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            80% { transform: translateY(-5px); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .cancel-btn, .save-btn {
                width: 100%;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // GSAP Timeline para animações de entrada
            const tl = gsap.timeline();
            
            // Animar cards de entrada
            tl.from('.bot-info-card', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                ease: 'power3.out'
            })
            .from('.services-card', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                ease: 'power3.out'
            }, '-=0.6')
            .from('.action-card', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                ease: 'power3.out'
            }, '-=0.6')
            
            // Animar elementos internos
            .from('.form-group-animated', {
                duration: 0.6,
                x: -30,
                opacity: 0,
                stagger: 0.1,
                ease: 'power2.out'
            }, '-=0.4')
            
            .from('.service-item', {
                duration: 0.5,
                scale: 0,
                opacity: 0,
                stagger: 0.05,
                ease: 'back.out(1.7)'
            }, '-=0.3');

            // Animações de hover para inputs
            const inputs = document.querySelectorAll('.animated-input, .animated-textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    gsap.to(this.closest('.form-group-animated').querySelector('.floating-label'), {
                        duration: 0.3,
                        y: -5,
                        color: '#667eea',
                        ease: 'power2.out'
                    });
                    
                    gsap.to(this.closest('.form-group-animated').querySelector('.label-icon'), {
                        duration: 0.3,
                        rotation: 360,
                        scale: 1.2,
                        ease: 'back.out(1.7)'
                    });
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        gsap.to(this.closest('.form-group-animated').querySelector('.floating-label'), {
                            duration: 0.3,
                            y: 0,
                            color: '#6c757d',
                            ease: 'power2.out'
                        });
                    }
                    
                    gsap.to(this.closest('.form-group-animated').querySelector('.label-icon'), {
                        duration: 0.3,
                        rotation: 0,
                        scale: 1,
                        ease: 'power2.out'
                    });
                });
            });

            // Animação do toggle de senha
            document.getElementById('toggleToken').addEventListener('click', function() {
                const tokenInput = document.getElementById('token_deepseek');
                const toggleIcon = document.getElementById('toggleIcon');
                
                gsap.to(toggleIcon, {
                    duration: 0.3,
                    rotation: 360,
                    scale: 1.2,
                    ease: 'back.out(1.7)',
                    onComplete: function() {
                        if (tokenInput.type === 'password') {
                            tokenInput.type = 'text';
                            toggleIcon.classList.remove('fa-eye');
                            toggleIcon.classList.add('fa-eye-slash');
                        } else {
                            tokenInput.type = 'password';
                            toggleIcon.classList.remove('fa-eye-slash');
                            toggleIcon.classList.add('fa-eye');
                        }
                        
                        gsap.to(toggleIcon, {
                            duration: 0.2,
                            rotation: 0,
                            scale: 1,
                            ease: 'power2.out'
                        });
                    }
                });
            });

            // Animação dos service cards
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach((card, index) => {
                card.addEventListener('mouseenter', function() {
                    gsap.to(card, {
                        duration: 0.4,
                        y: -10,
                        scale: 1.05,
                        rotationY: 5,
                        boxShadow: '0 25px 50px rgba(0,0,0,0.2)',
                        ease: 'power2.out'
                    });
                    
                    gsap.to(card.querySelector('.service-item-icon'), {
                        duration: 0.3,
                        rotation: 360,
                        scale: 1.3,
                        ease: 'back.out(1.7)'
                    });
                });
                
                card.addEventListener('mouseleave', function() {
                    gsap.to(card, {
                        duration: 0.4,
                        y: 0,
                        scale: 1,
                        rotationY: 0,
                        boxShadow: '0 10px 30px rgba(0,0,0,0.1)',
                        ease: 'power2.out'
                    });
                    
                    gsap.to(card.querySelector('.service-item-icon'), {
                        duration: 0.3,
                        rotation: 0,
                        scale: 1,
                        ease: 'power2.out'
                    });
                });
                
                card.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = this.querySelector('input[type="checkbox"]');
                        const wasChecked = checkbox.checked;
                        checkbox.checked = !checkbox.checked;
                        
                        // Animação de click
                        gsap.to(card, {
                            duration: 0.1,
                            scale: 0.95,
                            ease: 'power2.out',
                            onComplete: function() {
                                gsap.to(card, {
                                    duration: 0.3,
                                    scale: checkbox.checked ? 1.02 : 1,
                                    ease: 'back.out(1.7)'
                                });
                            }
                        });
                        
                        // Animação do checkbox
                        if (!wasChecked) {
                            gsap.fromTo(card.querySelector('.check-animation'), {
                                scale: 0,
                                opacity: 0
                            }, {
                                duration: 0.5,
                                scale: 1,
                                opacity: 1,
                                ease: 'back.out(1.7)'
                            });
                            
                            // Efeito de partículas
                            createCheckParticles(card);
                        } else {
                            gsap.to(card.querySelector('.check-animation'), {
                                duration: 0.3,
                                scale: 0,
                                opacity: 0,
                                ease: 'power2.in'
                            });
                        }
                        
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Função para criar partículas de confirmação
            function createCheckParticles(card) {
                const rect = card.getBoundingClientRect();
                const centerX = rect.left + rect.width / 2;
                const centerY = rect.top + rect.height / 2;
                
                for (let i = 0; i < 6; i++) {
                    const particle = document.createElement('div');
                    particle.style.cssText = `
                        position: fixed;
                        width: 4px;
                        height: 4px;
                        background: #667eea;
                        border-radius: 50%;
                        pointer-events: none;
                        z-index: 9999;
                        left: ${centerX}px;
                        top: ${centerY}px;
                    `;
                    document.body.appendChild(particle);
                    
                    const angle = (i / 6) * Math.PI * 2;
                    const distance = 50 + Math.random() * 30;
                    
                    gsap.to(particle, {
                        duration: 0.8,
                        x: Math.cos(angle) * distance,
                        y: Math.sin(angle) * distance,
                        opacity: 0,
                        scale: 0,
                        ease: 'power2.out',
                        onComplete: function() {
                            particle.remove();
                        }
                    });
                }
            }

            // Animação do switch
            const switchInput = document.getElementById('status');
            const switchLabel = document.querySelector('.switch-label');
            
            switchInput.addEventListener('change', function() {
                const switchText = switchLabel.querySelector('.switch-text');
                const switchIcon = switchLabel.querySelector('.switch-icon');
                
                gsap.to(switchIcon, {
                    duration: 0.3,
                    rotation: this.checked ? 360 : -360,
                    scale: 1.2,
                    ease: 'back.out(1.7)',
                    onComplete: function() {
                        gsap.to(switchIcon, {
                            duration: 0.2,
                            rotation: 0,
                            scale: 1,
                            ease: 'power2.out'
                        });
                    }
                });
                
                gsap.to(switchText, {
                    duration: 0.3,
                    color: this.checked ? '#00b894' : '#6c757d',
                    ease: 'power2.out'
                });
                
                if (this.checked) {
                    // Efeito de "liga"
                    gsap.to(switchLabel, {
                        duration: 0.2,
                        scale: 1.05,
                        ease: 'power2.out',
                        onComplete: function() {
                            gsap.to(switchLabel, {
                                duration: 0.2,
                                scale: 1,
                                ease: 'power2.out'
                            });
                        }
                    });
                }
            });

            // Animações dos botões
            const saveBtn = document.querySelector('.save-btn');
            const cancelBtn = document.querySelector('.cancel-btn');
            
            saveBtn.addEventListener('mouseenter', function() {
                gsap.to(this, {
                    duration: 0.3,
                    y: -5,
                    boxShadow: '0 15px 40px rgba(102, 126, 234, 0.4)',
                    ease: 'power2.out'
                });
                
                gsap.to(this.querySelector('.save-icon'), {
                    duration: 0.3,
                    rotation: 360,
                    ease: 'power2.out'
                });
            });
            
            saveBtn.addEventListener('mouseleave', function() {
                gsap.to(this, {
                    duration: 0.3,
                    y: 0,
                    boxShadow: '0 10px 30px rgba(0,0,0,0.1)',
                    ease: 'power2.out'
                });
                
                gsap.to(this.querySelector('.save-icon'), {
                    duration: 0.3,
                    rotation: 0,
                    ease: 'power2.out'
                });
            });
            
            cancelBtn.addEventListener('mouseenter', function() {
                gsap.to(this, {
                    duration: 0.3,
                    y: -3,
                    scale: 1.02,
                    ease: 'power2.out'
                });
            });
            
            cancelBtn.addEventListener('mouseleave', function() {
                gsap.to(this, {
                    duration: 0.3,
                    y: 0,
                    scale: 1,
                    ease: 'power2.out'
                });
            });

            // Animação de submit do formulário
            document.querySelector('form').addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('.save-btn');
                
                gsap.to(submitBtn, {
                    duration: 0.3,
                    scale: 0.95,
                    ease: 'power2.out',
                    onComplete: function() {
                        gsap.to(submitBtn, {
                            duration: 0.5,
                            scale: 1,
                            ease: 'back.out(1.7)'
                        });
                    }
                });
                
                // Animação de loading no botão
                const saveIcon = submitBtn.querySelector('.save-icon');
                gsap.to(saveIcon, {
                    duration: 0.8,
                    rotation: 720,
                    ease: 'power2.inOut',
                    repeat: -1
                });
                
                // Criar efeito de ondas no botão
                const waves = [];
                for (let i = 0; i < 3; i++) {
                    const wave = document.createElement('div');
                    wave.style.cssText = `
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        width: 0;
                        height: 0;
                        border: 2px solid rgba(255,255,255,0.5);
                        border-radius: 50%;
                        transform: translate(-50%, -50%);
                        pointer-events: none;
                    `;
                    submitBtn.appendChild(wave);
                    waves.push(wave);
                    
                    gsap.to(wave, {
                        duration: 1.5,
                        width: 200,
                        height: 200,
                        opacity: 0,
                        ease: 'power2.out',
                        delay: i * 0.3,
                        repeat: -1
                    });
                }
            });

            // Auto-resize do textarea com animação
            const textarea = document.getElementById('prompt');
            textarea.addEventListener('input', function() {
                gsap.to(this, {
                    duration: 0.3,
                    height: 'auto',
                    ease: 'power2.out',
                    onComplete: function() {
                        gsap.to(textarea, {
                            duration: 0.3,
                            height: textarea.scrollHeight + 'px',
                            ease: 'power2.out'
                        });
                    }
                });
            });

            // Validação de formulário com animações
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                                
                                // Animar campos inválidos
                                const invalidFields = form.querySelectorAll(':invalid');
                                invalidFields.forEach(field => {
                                    gsap.fromTo(field, {
                                        x: -10
                                    }, {
                                        duration: 0.5,
                                        x: 10,
                                        ease: 'power2.inOut',
                                        yoyo: true,
                                        repeat: 3,
                                        onComplete: function() {
                                            gsap.set(field, { x: 0 });
                                        }
                                    });
                                    
                                    // Destacar label do campo inválido
                                    const label = field.closest('.form-group-animated')?.querySelector('.floating-label');
                                    if (label) {
                                        gsap.to(label, {
                                            duration: 0.3,
                                            color: '#dc3545',
                                            scale: 1.05,
                                            ease: 'power2.out'
                                        });
                                    }
                                });
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();

            // Animação de scroll suave
            gsap.registerPlugin(ScrollTrigger);
            
            gsap.utils.toArray('.card').forEach((card, index) => {
                gsap.fromTo(card, {
                    opacity: 0,
                    y: 50,
                    rotationX: 15
                }, {
                    opacity: 1,
                    y: 0,
                    rotationX: 0,
                    duration: 0.8,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 85%',
                        toggleActions: 'play none none reverse'
                    }
                });
            });

            // Efeito parallax sutil no background
            gsap.to('body', {
                backgroundPosition: '50% 10%',
                ease: 'none',
                scrollTrigger: {
                    trigger: 'body',
                    start: 'top top',
                    end: 'bottom top',
                    scrub: 1
                }
            });

            // Animação de loading inicial
            gsap.set('.page-wrapper', { opacity: 0 });
            gsap.to('.page-wrapper', {
                duration: 1,
                opacity: 1,
                ease: 'power2.out',
                delay: 0.2
            });

            // Efeito de typing no placeholder (apenas visual)
            function createTypingEffect(element, text, speed = 50) {
                let i = 0;
                const originalPlaceholder = element.placeholder;
                element.placeholder = '';
                
                function typeChar() {
                    if (i < text.length) {
                        element.placeholder += text.charAt(i);
                        i++;
                        setTimeout(typeChar, speed);
                    }
                }
                
                setTimeout(typeChar, 1000);
            }

            // Aplicar efeito de typing em alguns placeholders
            const promptTextarea = document.getElementById('prompt');
            if (promptTextarea && !promptTextarea.value) {
                createTypingEffect(
                    promptTextarea, 
                    'Você é um assistente especializado em aulas de surf, sempre amigável e motivador...',
                    30
                );
            }

        });
    </script>
    @endpush
</x-admin.layout>