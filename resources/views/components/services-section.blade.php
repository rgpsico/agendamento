<section id="services" class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 section-header">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                {{ $site->servicos_titulo ?? 'Nossos Serviços de ' . ucfirst($modalidade) }}
            </h2>
            <div class="section-divider max-w-md mx-auto"></div>
            <p class="text-gray-600 text-xl max-w-2xl mx-auto">
                {{ $site->servicos_descricao ?? 'Oferecemos uma experiência completa para todos os níveis.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 services-grid">
            @foreach($servicos as $servico)
                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg relative">
                    @if($servico['destaque'] ?? $servico->destaque)
                        <div class="absolute top-4 left-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold z-10">
                            Mais Popular
                        </div>
                    @endif
                    <div class="h-48 relative overflow-hidden rounded-md shadow-md">
                    <img 
                        src="{{ asset('servico/' . ($servico['imagem'] ?? $servico->imagem)) }}" 
                        alt="{{ $servico['titulo'] ?? $servico->titulo }}"                    
                        class="w-full h-full object-cover"
                    />

                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>

                    <!-- Ícone -->
                    <div class="absolute top-4 right-4 bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-full p-3">
                        <i class="fas {{ $servico['icone'] ?? $servico->icone ?? 'fa-water' }} text-white text-xl"></i>
                    </div>
                </div>

                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $servico['titulo'] ?? $servico->titulo }}</h3>
                        <p class="text-gray-600 mb-4">{{ $servico['descricao'] ?? $servico->descricao }}</p>

                        @if(!empty($servico['features'] ?? $servico->features))
                            <ul class="mb-6 text-gray-600 space-y-2">
                                @foreach($servico['features'] ?? $servico->features as $feature)
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-3"></i>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="flex justify-between items-center">
                            @if(isset($servico['preco']) || isset($servico->preco))
                                <span class="text-3xl font-bold service-icon">
                                    R$ {{ number_format($servico['preco'] ?? $servico->preco, 2, ',', '.') }}
                                </span>
                            @endif
                            <button class="btn-primary text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 hover:scale-105">
                                Reservar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
