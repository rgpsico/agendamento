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
    <!-- About Section -->
<section id="about" class="py-20 bg-white parallax-bg" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ $site->sobre_imagem ? asset('storage/' . $site->sobre_imagem) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80' }}');">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="about-content">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 about-title">{{ $site->sobre_titulo ?? 'Por que escolher a OceanWave?' }}</h2>
                <p class="text-gray-200 text-lg mb-8 about-description">{{ $site->sobre_descricao ?? 'Com mais de 15 anos de experiência, somos a escola de surf líder na região.' }}</p>
                
                <div class="space-y-6 about-features">
                    @foreach($site->sobre_itens ?? [
                        ['icone' => 'fa-award', 'titulo' => 'Instrutores Certificados', 'descricao' => 'Todos nossos instrutores possuem certificação internacional e anos de experiência.'],
                        ['icone' => 'fa-leaf', 'titulo' => 'Sustentabilidade', 'descricao' => 'Comprometidos com a preservação do meio ambiente e oceanos limpos.'],
                        ['icone' => 'fa-heart', 'titulo' => 'Comunidade', 'descricao' => 'Mais que uma escola, somos uma família de amantes do surf.']
                    ] as $item)
                        <div class="flex items-start glass-card p-4 rounded-lg about-feature">
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
                    ] as $index => $imagem)
                        <img src="{{ $imagem }}" alt="Imagem sobre" class="rounded-lg shadow-lg about-image-item" data-index="{{ $index }}">
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
                            <div>
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
                                        <p class="text-gray-600">{{ $site->endereco->endereco ?? 'Av. Beira Mar, 1234<br>Praia do Sol - SP, 11000-000' }}</p>
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
                                        <p class="text-gray-600">{{ $site->empresa->user->email ?? 'contato@oceanwave.com.br' }}</p>
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


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/site-metrics.js') }}"></script>
<script>
    window.SITE_ID = {{ $site->id ?? 1 }};
    window.WHATSAPP = '{{ preg_replace("/[^0-9]/", "", $site->whatsapp ?? "1199999888") }}';
</script>


</script>
   @include('site._partials.footer')

    <!-- WhatsApp Floating Button -->