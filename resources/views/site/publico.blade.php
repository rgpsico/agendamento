@include('site._partials.header')

<body class="font-sans bg-gray-50 overflow-x-hidden">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loading">
        <div class="wave-loader"></div>
        <p class="text-white text-xl mt-4 loading-text">Preparando as ondas...</p>
    </div>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/{{ $site->whatsapp  ?? '5511999999999' }}?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20seus%20serviços" class="diagonal-whatsapp floating">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Navigation -->
    @include('site._partials.menu')

    <!-- Hero Section -->
    <x-hero-section 
        :title="$site->titulo ?? 'Aprenda a surfar com os melhores instrutores'"
        :subtitle="$site->descricao ?? 'Viva a experiência única do oceano'"
        :image="$site->capa ? asset('storage/' . $site->capa) : null"
        :primary-button="['text' => 'Começar Agora', 'link' => '/inscricao', 'icon' => 'fas fa-water']"
        :secondary-button="['text' => 'Ver Vídeo', 'link' => '/video', 'icon' => 'fas fa-play']"
    />

    <!-- Stats Section -->
    {{-- <section class="py-0 bg-white relative">
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
    </section> --}}

    <!-- Services Section -->
   <x-services-section :site="$site" modalidade="surf" />

    <!-- About Section -->
    <x-site.about-section :site="$site" />
    <!-- About Section -->


    <!-- Testimonials Section -->
 <x-site.testimonials-section :site="$site" />
       
    <!-- CTA Section -->
    <section class="py-20 hero-gradient text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h2 class="text-4xl md:text-6xl font-bold mb-6 text-glow cta-title">{{ $site->cta_titulo ?? 'Pronto para Pegar Sua Primeira Onda?' }}</h2>
            <p class="text-xl md:text-2xl mb-8 opacity-90 cta-subtitle">{{ $site->cta_descricao ?? 'Junte-se a centenas de alunos que já transformaram suas vidas através do surf' }}</p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 cta-buttons">
                <button class="bg-white text-gray-800 hover:bg-gray-100 px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all duration-300">
                    <i class="fas fa-phone mr-2"></i>
                    {{ $site->whatsapp  ?? '(11) 99999-888' }}
                </button>
                <button  class="border-2 border-white text-white hover:bg-white hover:text-gray-800 px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300">
                    <i class="fab fa-whatsapp mr-2"></i>
                    WhatsApp
                </button>
            </div>
        </div>
    </section>




    <!-- Contact Section -->
   <x-site.contact-section :site="$site" />


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/site-metrics.js') }}"></script>
<script>
    window.SITE_ID = {{ $site->id ?? 1 }};
    window.WHATSAPP = '{{ preg_replace("/[^0-9]/", "", $site->whatsapp ?? "1199999888") }}';
</script>


</script>
   @include('site._partials.footer')

    <!-- WhatsApp Floating Button -->