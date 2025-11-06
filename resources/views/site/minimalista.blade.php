@include('site._partials.header')

<body class="bg-slate-950 text-slate-100 antialiased overflow-x-hidden">
    @include('site._partials.menu')

    <main class="relative">
        <!-- Hero Section com efeitos visuais aprimorados -->
        <section id="home" class="relative pt-32 pb-24 overflow-hidden">
            <!-- Gradient animado de fundo -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900"></div>
            
            <!-- Efeito de grid sutil -->
            <div class="absolute inset-0 opacity-20" style="background-image: 
                linear-gradient(rgba(16, 185, 129, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16, 185, 129, 0.1) 1px, transparent 1px);
                background-size: 50px 50px;">
            </div>
            
            <!-- Orbes luminosos -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/20 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 1s;"></div>
            
            <div class="container mx-auto px-6 relative z-10">
                <div class="grid gap-12 lg:grid-cols-[1.2fr,1fr] items-center">
                    <div class="space-y-6 animate-fade-in-up">
                        <!-- Badge empresarial -->
                        <div class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium tracking-wide rounded-full 
                                    bg-gradient-to-r from-emerald-500/20 to-blue-500/20 
                                    border border-emerald-400/30 text-slate-200
                                    backdrop-blur-sm shadow-lg shadow-emerald-500/10
                                    hover:shadow-emerald-500/20 transition-all duration-300">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            {{ $site->empresa->nome_fantasia ?? $site->titulo ?? 'Studio One' }}
                        </div>

                        <!-- Título principal com gradiente -->
                        <h1 class="text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl 
                                   bg-gradient-to-r from-white via-slate-100 to-slate-300 
                                   bg-clip-text text-transparent
                                   animate-fade-in-up"
                            style="animation-delay: 0.1s;">
                            {{ $site->titulo ?? 'Um template elegante para contar a sua história' }}
                        </h1>

                        <!-- Descrição -->
                        <p class="max-w-2xl text-lg text-slate-300 leading-relaxed animate-fade-in-up"
                           style="animation-delay: 0.2s;">
                            {{ $site->descricao ?? 'Apresente seus serviços com uma experiência digital envolvente, focada em conversões e relacionamento com seus clientes.' }}
                        </p>

                        <!-- CTAs -->
                        <div class="flex flex-wrap items-center gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                            <a href="#contact" 
                               class="group relative px-8 py-4 text-base font-semibold rounded-full overflow-hidden
                                      transition-all duration-300 shadow-lg hover:shadow-emerald-500/50 hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-emerald-400 
                                            group-hover:from-emerald-400 group-hover:to-emerald-300 transition-all duration-300"></div>
                                <span class="relative z-10 flex items-center gap-2">
                                    Fale Conosco
                                    <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                                </span>
                            </a>
                            
                            @if($site->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site->whatsapp) }}" 
                                   target="_blank"
                                   class="group flex items-center gap-2 px-8 py-4 text-base font-semibold 
                                          rounded-full border-2 border-slate-700/50 backdrop-blur-sm
                                          hover:border-emerald-400 hover:bg-emerald-500/10 
                                          transition-all duration-300 hover:scale-105">
                                    <i class="fab fa-whatsapp text-xl group-hover:scale-110 transition-transform"></i>
                                    WhatsApp
                                </a>
                            @endif
                        </div>

                        <!-- Estatísticas -->
                        <dl class="grid gap-6 pt-8 sm:grid-cols-3 animate-fade-in-up" style="animation-delay: 0.4s;">
                            <div class="group p-4 rounded-2xl border border-slate-800/50 bg-slate-900/30 backdrop-blur-sm
                                        hover:border-emerald-400/50 hover:bg-slate-900/50 transition-all duration-300">
                                <dt class="text-xs uppercase tracking-[0.25em] text-slate-500 group-hover:text-emerald-400 transition-colors">
                                    Clientes Ativos
                                </dt>
                                <dd class="mt-2 text-3xl font-bold bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">
                                    {{ $site->empresa->clientes_ativos ?? '250+' }}
                                </dd>
                            </div>
                            <div class="group p-4 rounded-2xl border border-slate-800/50 bg-slate-900/30 backdrop-blur-sm
                                        hover:border-emerald-400/50 hover:bg-slate-900/50 transition-all duration-300">
                                <dt class="text-xs uppercase tracking-[0.25em] text-slate-500 group-hover:text-emerald-400 transition-colors">
                                    Anos de experiência
                                </dt>
                                <dd class="mt-2 text-3xl font-bold bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">
                                    {{ $site->empresa->anos_experiencia ?? '10' }}
                                </dd>
                            </div>
                            <div class="group p-4 rounded-2xl border border-slate-800/50 bg-slate-900/30 backdrop-blur-sm
                                        hover:border-emerald-400/50 hover:bg-slate-900/50 transition-all duration-300">
                                <dt class="text-xs uppercase tracking-[0.25em] text-slate-500 group-hover:text-emerald-400 transition-colors">
                                    Avaliação média
                                </dt>
                                <dd class="mt-2 text-3xl font-bold flex items-center gap-2">
                                    <span class="bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">
                                        {{ number_format($site->avaliacao_media ?? 4.9, 1) }}
                                    </span>
                                    <span class="text-emerald-400 text-sm">/ 5.0</span>
                                    <div class="flex gap-0.5 ml-1">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star text-emerald-400 text-xs"></i>
                                        @endfor
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Imagem hero com efeitos -->
                    <div class="relative animate-fade-in-up" style="animation-delay: 0.5s;">
                        <!-- Efeito de brilho no fundo -->
                        <div class="absolute -inset-4 bg-gradient-to-r from-emerald-500/20 to-blue-500/20 rounded-3xl blur-2xl opacity-50 animate-pulse"></div>
                        
                        <div class="relative rounded-3xl border-2 border-slate-800/50 bg-gradient-to-br from-slate-900/70 to-slate-950/70 p-6 
                                    backdrop-blur-xl shadow-2xl hover:border-emerald-400/30 transition-all duration-500 group">
                            <div class="aspect-[4/5] rounded-2xl overflow-hidden border border-slate-800/70 bg-slate-900/50 
                                        shadow-inner relative group-hover:scale-[1.02] transition-transform duration-500">
                                @if($site->capa)
                                    <img src="{{ asset('storage/' . $site->capa) }}" 
                                         alt="{{ $site->titulo }}"
                                         class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-700" 
                                         loading="lazy">
                                    <!-- Overlay gradiente -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <div class="text-center space-y-2">
                                            <i class="fas fa-image text-4xl text-slate-600"></i>
                                            <span class="block text-sm text-slate-500">Adicione uma imagem de capa no painel</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card flutuante de diferencial -->
                        <div class="absolute -bottom-10 -left-6 hidden md:block animate-fade-in-up" style="animation-delay: 0.7s;">
                            <div class="group rounded-2xl border-2 border-emerald-400/40 bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 
                                        px-6 py-5 backdrop-blur-xl shadow-lg shadow-emerald-500/20
                                        hover:shadow-emerald-500/30 hover:scale-105 transition-all duration-300 max-w-xs">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/30 text-emerald-300">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-emerald-300 font-semibold">Diferencial</p>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-white leading-relaxed">
                                    {{ $site->empresa->diferencial ?? 'Estratégias exclusivas para acelerar suas vendas.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="relative py-24 bg-slate-950">
            <!-- Linha decorativa -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-500/30 to-transparent"></div>
            
            <div class="container mx-auto px-6">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-4 max-w-2xl">
                        <span class="inline-block text-sm font-semibold uppercase tracking-[0.35em] text-emerald-400/80">
                            Soluções
                        </span>
                        <h2 class="text-3xl font-bold text-white sm:text-4xl bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">
                            {{ $site->empresa->tagline ?? 'Tudo o que você precisa para crescer' }}
                        </h2>
                        <p class="text-base text-slate-400 leading-relaxed">
                            {{ $site->empresa->descricao_curta ?? 'Apresente seus principais serviços com clareza e convença novos clientes com resultados reais.' }}
                        </p>
                    </div>
                    
                    <div class="flex items-start gap-4 p-6 rounded-2xl border border-slate-800/50 bg-slate-900/30 backdrop-blur-sm max-w-md">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full 
                                    bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 
                                    border border-emerald-400/30 flex-shrink-0">
                            <i class="fas fa-check text-emerald-400 text-lg"></i>
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed">
                            Automatize comunicações, centralize agendamentos e acompanhe suas métricas em tempo real.
                        </p>
                    </div>
                </div>
                
                <div class="mt-16">
                    <x-services-section :site="$site" />
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="relative py-24 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950"></div>
            
            <!-- Decoração de fundo -->
            <div class="absolute top-1/2 left-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px] -translate-y-1/2"></div>
            <div class="absolute top-1/2 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px] -translate-y-1/2"></div>
            
            <div class="container mx-auto px-6 relative z-10">
                <div class="grid gap-12 lg:grid-cols-[1.1fr,0.9fr] items-center">
                    <div class="order-2 lg:order-1">
                        <x-site.about-section :site="$site" />
                    </div>
                    
                    <div class="order-1 lg:order-2">
                        <div class="relative rounded-3xl border-2 border-slate-800/50 bg-gradient-to-br from-slate-900/60 to-slate-950/60 
                                    p-8 backdrop-blur-xl shadow-2xl">
                            <div class="space-y-6">
                                <!-- Feature 1 -->
                                <div class="group flex items-start gap-4 p-4 rounded-2xl 
                                            hover:bg-slate-800/30 transition-all duration-300">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl 
                                                bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 
                                                border border-emerald-400/30 text-emerald-400 flex-shrink-0
                                                group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-emerald-500/20 transition-all duration-300">
                                        <i class="fas fa-bolt text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white group-hover:text-emerald-400 transition-colors">
                                            Integração completa
                                        </h3>
                                        <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                                            Conecte atendimento, pagamentos e marketing em um único lugar.
                                        </p>
                                    </div>
                                </div>

                                <!-- Feature 2 -->
                                <div class="group flex items-start gap-4 p-4 rounded-2xl 
                                            hover:bg-slate-800/30 transition-all duration-300">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl 
                                                bg-gradient-to-br from-blue-500/20 to-blue-600/10 
                                                border border-blue-400/30 text-blue-400 flex-shrink-0
                                                group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-blue-500/20 transition-all duration-300">
                                        <i class="fas fa-chart-line text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white group-hover:text-blue-400 transition-colors">
                                            Resultados mensuráveis
                                        </h3>
                                        <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                                            Use dashboards e relatórios para acompanhar o crescimento do negócio.
                                        </p>
                                    </div>
                                </div>

                                <!-- Feature 3 -->
                                <div class="group flex items-start gap-4 p-4 rounded-2xl 
                                            hover:bg-slate-800/30 transition-all duration-300">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl 
                                                bg-gradient-to-br from-purple-500/20 to-purple-600/10 
                                                border border-purple-400/30 text-purple-400 flex-shrink-0
                                                group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-purple-500/20 transition-all duration-300">
                                        <i class="fas fa-lock text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white group-hover:text-purple-400 transition-colors">
                                            Segurança em primeiro lugar
                                        </h3>
                                        <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                                            Proteção avançada de dados e autenticação segura para sua equipe.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid Section -->
        <section class="py-24 bg-slate-950 relative">
            <div class="container mx-auto px-6">
                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Card 1 - Destaque -->
                    <div class="group relative rounded-3xl border-2 border-emerald-400/30 
                                bg-gradient-to-br from-emerald-500/10 to-emerald-600/5 p-8
                                hover:border-emerald-400/50 hover:shadow-lg hover:shadow-emerald-500/20 
                                transition-all duration-300 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl 
                                    group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative z-10">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl 
                                        bg-emerald-500/20 text-emerald-400 mb-4">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">Time especializado</h3>
                            <p class="text-sm text-slate-300 leading-relaxed">
                                {{ $site->empresa->time_especializado ?? 'Profissionais dedicados a oferecer uma jornada completa para seus clientes.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="group relative rounded-3xl border-2 border-slate-800/50 
                                bg-gradient-to-br from-slate-900/60 to-slate-950/60 p-8
                                hover:border-emerald-400/30 hover:shadow-lg hover:shadow-emerald-500/10 
                                transition-all duration-300 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl 
                                    group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative z-10">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl 
                                        bg-blue-500/20 text-blue-400 mb-4">
                                <i class="fas fa-microchip text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">Tecnologia inteligente</h3>
                            <p class="text-sm text-slate-300 leading-relaxed">
                                {{ $site->empresa->tecnologia ?? 'Fluxos automatizados, métricas claras e integrações com as principais plataformas.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="group relative rounded-3xl border-2 border-slate-800/50 
                                bg-gradient-to-br from-slate-900/60 to-slate-950/60 p-8
                                hover:border-emerald-400/30 hover:shadow-lg hover:shadow-emerald-500/10 
                                transition-all duration-300 overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl 
                                    group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative z-10">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl 
                                        bg-purple-500/20 text-purple-400 mb-4">
                                <i class="fas fa-comments text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-3">Atendimento humanizado</h3>
                            <p class="text-sm text-slate-300 leading-relaxed">
                                {{ $site->empresa->atendimento ?? 'Transforme leads em fãs com comunicações personalizadas em cada etapa.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="relative py-24 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950"></div>
            
            <div class="container mx-auto px-6 relative z-10">
                <div class="max-w-3xl mx-auto text-center mb-16">
                    <span class="inline-block text-sm font-semibold uppercase tracking-[0.35em] text-emerald-400/80 mb-4">
                        Confiado por quem entrega resultados
                    </span>
                    <h2 class="text-3xl font-bold sm:text-4xl mb-6
                               bg-gradient-to-r from-white via-slate-100 to-slate-300 bg-clip-text text-transparent">
                        Histórias reais de evolução
                    </h2>
                    <p class="text-base text-slate-400 leading-relaxed max-w-2xl mx-auto">
                        {{ $site->empresa->depoimentos_texto ?? 'Empresas e profissionais de diferentes segmentos já transformaram seus processos com nossos templates.' }}
                    </p>
                </div>
                
                <div class="mt-12">
                    <x-site.testimonials-section :site="$site" />
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="relative py-24 bg-slate-950 overflow-hidden">
            <!-- Decoração de fundo -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/10 rounded-full blur-[120px]"></div>
            
            <div class="container mx-auto px-6 relative z-10">
                <div class="grid gap-12 lg:grid-cols-[1fr,1fr] items-center">
                    <div class="space-y-6">
                        <span class="inline-block text-sm font-semibold uppercase tracking-[0.35em] text-emerald-400/80">
                            Pronto para começar?
                        </span>
                        <h2 class="text-3xl font-bold text-white sm:text-4xl bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">
                            Vamos construir uma presença digital memorável
                        </h2>
                        <p class="text-base text-slate-400 leading-relaxed">
                            Entre em contato para criarmos uma experiência personalizada para sua marca.
                        </p>
                        
                        <ul class="space-y-4 pt-4">
                            @if($site->empresa && $site->empresa->telefone)
                                <li class="group flex items-center gap-4 p-4 rounded-2xl border border-slate-800/50 
                                           bg-slate-900/30 backdrop-blur-sm hover:border-emerald-400/30 hover:bg-slate-900/50 
                                           transition-all duration-300">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-xl 
                                                 bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 
                                                 border border-emerald-400/30 text-emerald-400
                                                 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <span class="text-slate-200">{{ $site->empresa->telefone }}</span>
                                </li>
                            @endif
                            
                            @if($site->empresa && $site->empresa->email)
                                <li class="group flex items-center gap-4 p-4 rounded-2xl border border-slate-800/50 
                                           bg-slate-900/30 backdrop-blur-sm hover:border-emerald-400/30 hover:bg-slate-900/50 
                                           transition-all duration-300">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-xl 
                                                 bg-gradient-to-br from-blue-500/20 to-blue-600/10 
                                                 border border-blue-400/30 text-blue-400
                                                 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <span class="text-slate-200">{{ $site->empresa->email }}</span>
                                </li>
                            @endif
                            
                            <li class="group flex items-center gap-4 p-4 rounded-2xl border border-slate-800/50 
                                       bg-slate-900/30 backdrop-blur-sm hover:border-emerald-400/30 hover:bg-slate-900/50 
                                       transition-all duration-300">
                                <span class="flex h-12 w-12 items-center justify-center rounded-xl 
                                             bg-gradient-to-br from-purple-500/20 to-purple-600/10 
                                             border border-purple-400/30 text-purple-400
                                             group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <span class="text-slate-200">
                                    {{ $site->empresa->endereco_completo ?? 'Disponível mediante solicitação.' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="relative">
                        <!-- Efeito de brilho -->
                        <div class="absolute -inset-4 bg-gradient-to-r from-emerald-500/20 to-blue-500/20 rounded-3xl blur-2xl opacity-50"></div>
                        
                        <div class="relative rounded-3xl border-2 border-slate-800/50 
                                    bg-gradient-to-br from-slate-900/70 to-slate-950/70 p-8 
                                    backdrop-blur-xl shadow-2xl">
                            <x-site.contact-section :site="$site" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Widgets de atendimento -->
    @if($site->atendimento_com_ia)
        <x-batepapo :site="$site" />
    @endif

    @if($site->atendimento_com_whatsapp)
        <x-atendimentowhatsapp :numero="$site->whatsapp" mensagem="Olá! Gostaria de saber mais sobre os serviços." />
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/site-metrics.js') }}"></script>
    <script>
        window.SITE_ID = {{ $site->id ?? 1 }};
        window.WHATSAPP = '{{ preg_replace("/[^0-9]/", "", $site->whatsapp ?? "1199999888") }}';
    </script>

    <!-- Adicionar CSS customizado para animações -->
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
            opacity: 0;
        }

        /* Scroll suave */
        html {
            scroll-behavior: smooth;
        }

        /* Efeito de hover em cards */
        .group:hover {
            transform: translateY(-2px);
        }
    </style>

    @include('site._partials.footer')
</body>