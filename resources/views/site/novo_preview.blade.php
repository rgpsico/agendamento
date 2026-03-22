@include('site._partials.header')

<body class="bg-slate-900 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white overflow-x-hidden">

    @include('site._partials.menu')

    <main>
        {{-- Section: Home (Hero) --}}
        <section id="home" class="relative min-h-screen flex items-center pt-20">
            <x-hero-section
                :title="$site->titulo ?? 'Bem-vindo'"
                :subtitle="$site->descricao ?? 'Soluções pensadas para o seu negócio.'"
                :image="$site->capa ? asset('storage/' . $site->capa) : asset('images/default-hero.jpg')"
                class="gsap-reveal-hero"
            />
        </section>

        {{-- Section: Services --}}
        @if($site->siteServicos && $site->siteServicos->count() > 0)
            <section id="services" class="py-24 bg-slate-800/50 backdrop-blur-sm relative overflow-hidden">
                <div class="container mx-auto px-4 gsap-reveal-section">
                    <div class="mb-16 text-center">
                        <h2 class="text-4xl font-bold tracking-tight text-white mb-4">Nossos Serviços</h2>
                        <div class="h-1 w-20 bg-indigo-500 mx-auto rounded-full"></div>
                    </div>
                    <x-services-section :site="$site" />
                </div>
            </section>
        @endif

        {{-- Section: About --}}
        <section id="about" class="py-24 bg-slate-900">
            <div class="container mx-auto px-4 gsap-reveal-section">
                <x-site.about-section :site="$site" />
            </div>
        </section>

        {{-- Section: Testimonials --}}
        @if($site->depoimentos && $site->depoimentos->count() > 0)
            <section id="testimonials" class="py-24 bg-indigo-900/10 border-y border-slate-800">
                <div class="container mx-auto px-4 gsap-reveal-section">
                    <div class="mb-12 text-center">
                        <h2 class="text-3xl font-bold text-white">O que dizem nossos clientes</h2>
                    </div>
                    <x-site.testimonials-section :site="$site" />
                </div>
            </section>
        @endif

        {{-- Section: Contact --}}
        <section id="contact" class="py-24 bg-slate-900 relative">
            <div class="container mx-auto px-4 gsap-reveal-section">
                <x-site.contact-section :site="$site" />
            </div>
        </section>
    </main>

    {{-- Floating Components & Interactivity --}}
    @if($site->atendimento_com_ia)
        <div class="fixed bottom-24 right-6 z-50">
            <x-batepapo :site="$site" />
        </div>
    @endif

    @if($site->atendimento_com_whatsapp)
        <div class="fixed bottom-6 right-6 z-50 transition-transform hover:scale-110 active:scale-95">
            <x-atendimentowhatsapp
                :numero="$site->whatsapp ?? ''"
                mensagem="Olá! Gostaria de atendimento."
            />
        </div>
    @endif

    {{-- Metrics & Scripts --}}
    <script>
        window.SITE_ID = {{ $site->id ?? 0 }};
        window.WHATSAPP = '{{ preg_replace('/\D/', '', $site->whatsapp ?? '') }}';
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="{{ asset('js/site-metrics.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            gsap.from('.gsap-reveal-hero', {
                duration: 1.2,
                y: 60,
                opacity: 0,
                ease: 'power4.out',
                delay: 0.2
            });

            const sections = gsap.utils.toArray('.gsap-reveal-section');
            sections.forEach((section) => {
                gsap.from(section, {
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 85%',
                        toggleActions: 'play none none none'
                    },
                    duration: 1,
                    y: 40,
                    opacity: 0,
                    ease: 'power2.out'
                });
            });
        });
    </script>

    @include('site._partials.footer')
