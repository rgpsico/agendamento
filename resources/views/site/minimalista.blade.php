@include('site._partials.header')

<body class="bg-slate-950 text-slate-100 antialiased overflow-x-hidden">
    @include('site._partials.menu')

    <main class="relative">
        <section id="home" class="pt-32 pb-24 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900">
            <div class="container mx-auto px-6">
                <div class="grid gap-12 lg:grid-cols-[1.2fr,1fr] items-center">
                    <div class="space-y-6">
                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium tracking-wide rounded-full bg-slate-800/60 text-slate-200">
                            {{ $site->empresa->nome_fantasia ?? $site->titulo ?? 'Studio One' }}
                        </span>
                        <h1 class="text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                            {{ $site->titulo ?? 'Um template elegante para contar a sua história' }}
                        </h1>
                        <p class="max-w-2xl text-lg text-slate-300">
                            {{ $site->descricao ?? 'Apresente seus serviços com uma experiência digital envolvente, focada em conversões e relacionamento com seus clientes.' }}
                        </p>
                        <div class="flex flex-wrap items-center gap-4">
                            <a href="#contact" class="px-6 py-3 text-base font-semibold transition-all duration-300 rounded-full shadow-lg bg-emerald-500 hover:bg-emerald-400 hover:shadow-emerald-500/50">
                                Fale Conosco
                            </a>
                            @if($site->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site->whatsapp) }}" target="_blank"
                                   class="flex items-center gap-2 px-6 py-3 text-base font-semibold transition-all duration-300 rounded-full border border-slate-700 hover:border-emerald-400">
                                    <i class="fab fa-whatsapp text-lg"></i>
                                    WhatsApp
                                </a>
                            @endif
                        </div>
                        <dl class="grid gap-6 pt-6 sm:grid-cols-3">
                            <div>
                                <dt class="text-xs uppercase tracking-[0.25em] text-slate-500">Clientes Ativos</dt>
                                <dd class="mt-2 text-2xl font-semibold">{{ $site->empresa->clientes_ativos ?? '250+' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-[0.25em] text-slate-500">Anos de experiência</dt>
                                <dd class="mt-2 text-2xl font-semibold">{{ $site->empresa->anos_experiencia ?? '10' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-[0.25em] text-slate-500">Avaliação média</dt>
                                <dd class="mt-2 text-2xl font-semibold flex items-center gap-2">
                                    <span>{{ number_format($site->avaliacao_media ?? 4.9, 1) }}</span>
                                    <span class="text-emerald-400 text-sm">/ 5.0</span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div class="relative">
                        <div class="relative rounded-3xl border border-slate-800 bg-slate-900/70 p-6 shadow-[0_20px_60px_-30px_rgba(16,185,129,0.6)]">
                            <div class="aspect-[4/5] rounded-2xl overflow-hidden border border-slate-800/70 bg-slate-900/50">
                                @if($site->capa)
                                    <img src="{{ asset('storage/' . $site->capa) }}" alt="{{ $site->titulo }}"
                                         class="object-cover w-full h-full" loading="lazy">
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <span class="text-slate-500">Adicione uma imagem de capa no painel</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="absolute -bottom-10 -left-6 hidden md:flex gap-4">
                            <div class="rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-5 py-4 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.3em] text-emerald-300">Diferencial</p>
                                <p class="mt-2 text-base font-semibold text-white">
                                    {{ $site->empresa->diferencial ?? 'Estratégias exclusivas para acelerar suas vendas.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="relative py-24 bg-slate-950">
            <div class="container mx-auto px-6">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <span class="text-sm font-semibold uppercase tracking-[0.35em] text-emerald-400/80">Soluções</span>
                        <h2 class="mt-2 text-3xl font-bold text-white sm:text-4xl">{{ $site->empresa->tagline ?? 'Tudo o que você precisa para crescer' }}</h2>
                        <p class="mt-4 max-w-2xl text-base text-slate-400">
                            {{ $site->empresa->descricao_curta ?? 'Apresente seus principais serviços com clareza e convença novos clientes com resultados reais.' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-slate-400">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-800 bg-slate-900/70">
                            <i class="fas fa-check text-emerald-400"></i>
                        </span>
                        <p>Automatize comunicações, centralize agendamentos e acompanhe suas métricas em tempo real.</p>
                    </div>
                </div>
                <div class="mt-12">
                    <x-services-section :site="$site" />
                </div>
            </div>
        </section>

        <section id="about" class="py-24 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
            <div class="container mx-auto px-6">
                <div class="grid gap-12 lg:grid-cols-[1.1fr,0.9fr] items-center">
                    <div class="order-2 lg:order-1">
                        <x-site.about-section :site="$site" />
                    </div>
                    <div class="order-1 lg:order-2">
                        <div class="relative rounded-3xl border border-slate-800 bg-slate-900/60 p-6">
                            <div class="space-y-8">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white">Integração completa</h3>
                                        <p class="mt-2 text-sm text-slate-400">Conecte atendimento, pagamentos e marketing em um único lugar.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white">Resultados mensuráveis</h3>
                                        <p class="mt-2 text-sm text-slate-400">Use dashboards e relatórios para acompanhar o crescimento do negócio.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white">Segurança em primeiro lugar</h3>
                                        <p class="mt-2 text-sm text-slate-400">Proteção avançada de dados e autenticação segura para sua equipe.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-slate-950">
            <div class="container mx-auto px-6">
                <div class="grid gap-8 md:grid-cols-3">
                    <div class="rounded-3xl border border-emerald-400/30 bg-emerald-500/10 p-8">
                        <h3 class="text-lg font-semibold text-white">Time especializado</h3>
                        <p class="mt-3 text-sm text-slate-300">{{ $site->empresa->time_especializado ?? 'Profissionais dedicados a oferecer uma jornada completa para seus clientes.' }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8">
                        <h3 class="text-lg font-semibold text-white">Tecnologia inteligente</h3>
                        <p class="mt-3 text-sm text-slate-300">{{ $site->empresa->tecnologia ?? 'Fluxos automatizados, métricas claras e integrações com as principais plataformas.' }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/60 p-8">
                        <h3 class="text-lg font-semibold text-white">Atendimento humanizado</h3>
                        <p class="mt-3 text-sm text-slate-300">{{ $site->empresa->atendimento ?? 'Transforme leads em fãs com comunicações personalizadas em cada etapa.' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials" class="py-24 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
            <div class="container mx-auto px-6">
                <div class="max-w-2xl">
                    <span class="text-sm font-semibold uppercase tracking-[0.35em] text-emerald-400/80">Confiado por quem entrega resultados</span>
                    <h2 class="mt-3 text-3xl font-bold text-white sm:text-4xl">Histórias reais de evolução</h2>
                    <p class="mt-4 text-base text-slate-400">{{ $site->empresa->depoimentos_texto ?? 'Empresas e profissionais de diferentes segmentos já transformaram seus processos com nossos templates.' }}</p>
                </div>
                <div class="mt-12">
                    <x-site.testimonials-section :site="$site" />
                </div>
            </div>
        </section>

        <section id="contact" class="py-24 bg-slate-950">
            <div class="container mx-auto px-6">
                <div class="grid gap-12 lg:grid-cols-[1fr,1fr] items-center">
                    <div class="space-y-6">
                        <span class="text-sm font-semibold uppercase tracking-[0.35em] text-emerald-400/80">Pronto para começar?</span>
                        <h2 class="text-3xl font-bold text-white sm:text-4xl">Vamos construir uma presença digital memorável</h2>
                        <p class="text-base text-slate-400">Entre em contato para criarmos uma experiência personalizada para sua marca.</p>
                        <ul class="space-y-4 text-sm text-slate-300">
                            @if($site->empresa && $site->empresa->telefone)
                                <li class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    {{ $site->empresa->telefone }}
                                </li>
                            @endif
                            @if($site->empresa && $site->empresa->email)
                                <li class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    {{ $site->empresa->email }}
                                </li>
                            @endif
                            <li class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                {{ $site->empresa->endereco_completo ?? 'Disponível mediante solicitação.' }}
                            </li>
                        </ul>
                    </div>
                    <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-8 shadow-[0_20px_60px_-30px_rgba(16,185,129,0.6)]">
                        <x-site.contact-section :site="$site" />
                    </div>
                </div>
            </div>
        </section>
    </main>

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

    @include('site._partials.footer')
</body>
