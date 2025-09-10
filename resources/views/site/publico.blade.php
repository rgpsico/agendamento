@include('site._partials.header')

<body class="font-sans bg-gray-50 overflow-x-hidden">
    <!-- Loading Screen -->
    <div class="loading-screen" id="loading">
        <div class="wave-loader"></div>
        <p class="text-white text-xl mt-4 loading-text">espere um pouco....</p>
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

  
    <!-- Services Section -->
   <x-services-section :site="$site" modalidade="surf" />

    <!-- About Section -->
    <x-site.about-section :site="$site" />
    <!-- About Section -->


    <!-- Testimonials Section -->
 <x-site.testimonials-section :site="$site" />
       
    <!-- CTA Section -->
    @include('site._partials.ctasection')




    <!-- Contact Section -->
   <x-site.contact-section :site="$site" />

   @if($site->atendimento_com_ia)
   
    <x-batepapo />
    @endif


       {{-- @if($site->atendimento_com_whatsapp)
            <x-atendimentowhatsapp :numero="$site->whatsapp" mensagem="Olá! Gostaria de atendimento." />
       @endif --}}

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/site-metrics.js') }}"></script>
<script>
    window.SITE_ID = {{ $site->id ?? 1 }};
    window.WHATSAPP = '{{ preg_replace("/[^0-9]/", "", $site->whatsapp ?? "1199999888") }}';
</script>


</script>
   @include('site._partials.footer')

    <!-- WhatsApp Floating Button -->