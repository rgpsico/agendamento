<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $site->titulo }} - {{ $site->descricao ?? 'Site oficial' }}</title>
    
        {{-- Favicon opcional --}}
        @if(!empty($site->favicon))
            <link rel="icon" href="{{ asset('storage/' . $site->favicon) }}" type="image/png">
        @endif
    
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
        <style>
            .diagonal-whatsapp {
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 70px;
                height: 70px;
                background-color: #25D366;
                color: white;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 30px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                transform: rotate(15deg);
                transition: all 0.3s ease;
            }
    
            .diagonal-whatsapp:hover {
                transform: rotate(0deg) scale(1.1);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            }
    
            .wave-bg {
                background: linear-gradient(
                    135deg, 
                    {{ $site->cores['primaria'] ?? '#38b2ac' }} 0%, 
                    {{ $site->cores['secundaria'] ?? '#0ea5e9' }} 100%
                );
            }
    
            .surf-card:hover,
            .tour-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                            0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }
    
            .nav-link {
                position: relative;
            }
    
            .nav-link::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                bottom: 0;
                left: 0;
                background-color: white;
                transition: width 0.3s ease;
            }
    
            .nav-link:hover::after {
                width: 100%;
            }
    
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
    
            .floating {
                animation: float 3s ease-in-out infinite;
            }
        </style>

@php
$corPrimaria = $site->cores['primaria'] ?? '#0ea5e9';  // fallback azul padrão
$corSecundaria = $site->cores['secundaria'] ?? '#38b2ac';
@endphp

<style>
.wave-bg {
    background: linear-gradient(135deg, {{ $corSecundaria }} 0%, {{ $corPrimaria }} 100%);
}
.text-wave {
    color: {{ $corPrimaria }};
}
.bg-wave {
    background-color: {{ $corPrimaria }};
}
.border-wave {
    border-color: {{ $corPrimaria }};
}
.hover-bg-wave:hover {
    background-color: {{ $corPrimaria }};
}
</style>

    </head>
    
<body class="font-sans bg-gray-50">
    <!-- WhatsApp Button -->
    <a href="https://wa.me/5511999999999?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20seus%20serviços" class="diagonal-whatsapp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Navigation -->
    <nav class="wave-bg text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('storage/' . $site->logo) }}" alt="Logo {{ $site->titulo }}" class="h-12 w-350 rounded-full">
                  
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="nav-link">Início</a>
                    <a href="#surf" class="nav-link">Escola de Surf</a>
                   
                    <a href="#about" class="nav-link">Sobre Nós</a>
                    <a href="#contact" class="nav-link">Contato</a>
                </div>
                <button class="md:hidden focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="wave-bg text-white py-20">
        <div class="container mx-auto px-6 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-4">{{ $site->titulo }}</h1>
                <p class="text-xl mb-8 p-2">{{ $site->descricao }}</p>
                <div class="flex space-x-4">
                    <a href="#surf" class="bg-white text-wave hover:bg-gray-100 px-6 py-3 rounded-full font-semibold transition duration-300">Aulas de Surf</a>
                    <a href="#tours" class="border-2 border-white text-white hover:bg-white hover:text-teal-600 px-6 py-3 rounded-full font-semibold transition duration-300">Passeios</a>
                </div>
            </div>
            <div class="md:w-1/2 flex justify-center">
                @if($site->capa)
                    <img src="{{ asset('storage/' . $site->capa) }}" alt="Imagem principal do site" class="rounded-lg shadow-xl floating max-w-full h-auto">
                @endif
            </div>
        </div>
    </section>
    

    <!-- Surf School Section -->
    <section id="surf" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $site->titulo ?? 'Nossos Serviços' }}</h2>
                <div class="w-20 h-1 bg-teal-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    {{ $site->descricao ?? 'Confira abaixo nossas aulas e serviços disponíveis para você.' }}
                </p>
            </div>
    
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($site->servicos as $servico)
                    <div class="surf-card bg-white rounded-xl overflow-hidden shadow-lg transition duration-300">
                        @if($servico->imagem)
                            <img src="{{ asset('storage/' . $servico->imagem) }}" alt="{{ $servico->titulo }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $servico->titulo }}</h3>
                            <p class="text-gray-600 mb-4">{{ $servico->descricao }}</p>
    
                            @if(!empty($servico->features) && is_array($servico->features))
                                <ul class="mb-4 text-gray-600">
                                    @foreach($servico->features as $feature)
                                        <li class="mb-1">
                                            <i class="fas fa-check text-teal-500 mr-2"></i> {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <ul class="mb-4 text-gray-600">
                                <li class="mb-1"><i class="fas fa-check text-teal-500 mr-2"></i> Equipamento incluído</li>
                                <li class="mb-1"><i class="fas fa-check text-teal-500 mr-2"></i> 2 horas de aula</li>
                                <li class="mb-1"><i class="fas fa-check text-teal-500 mr-2"></i> Teoria e prática</li>
                            </ul>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-teal-600">R$ {{ number_format($servico->preco, 2, ',', '.') }}</span>
                                <a href="#contact" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-full transition duration-300">Reservar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    
    

    <!-- Tours Section -->
    {{-- <section id="tours" class="py-20 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Passeios Turísticos</h2>
                <div class="w-20 h-1 bg-teal-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Explore as belezas naturais da nossa região com nossos passeios exclusivos.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Tour Card 1 -->
                <div class="tour-card bg-white rounded-xl overflow-hidden shadow-lg transition duration-300">
                    <img src="https://via.placeholder.com/400x250" alt="Trilha Ecológica" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-800">Trilha Ecológica</h3>
                            <span class="bg-teal-100 text-teal-800 text-xs px-2 py-1 rounded-full">Duração: 4h</span>
                        </div>
                        <p class="text-gray-600 mb-4">Explore a mata atlântica preservada com nossos guias especializados.</p>
                        <div class="flex items-center text-gray-500 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Parque Estadual da Serra</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-teal-600">R$ 80</span>
                            <button class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-full transition duration-300">Reservar</button>
                        </div>
                    </div>
                </div>
                
                <!-- Tour Card 2 -->
                <div class="tour-card bg-white rounded-xl overflow-hidden shadow-lg transition duration-300">
                    <img src="https://via.placeholder.com/400x250" alt="Passeio de Barco" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-800">Passeio de Barco</h3>
                            <span class="bg-teal-100 text-teal-800 text-xs px-2 py-1 rounded-full">Duração: 3h</span>
                        </div>
                        <p class="text-gray-600 mb-4">Conheça as ilhas paradisíacas e faça snorkeling em águas cristalinas.</p>
                        <div class="flex items-center text-gray-500 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Arquipélago dos Alcatrazes</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-teal-600">R$ 150</span>
                            <button class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-full transition duration-300">Reservar</button>
                        </div>
                    </div>
                </div>
                
                <!-- Tour Card 3 -->
                <div class="tour-card bg-white rounded-xl overflow-hidden shadow-lg transition duration-300">
                    <img src="https://via.placeholder.com/400x250" alt="Tour Cultural" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-800">Tour Cultural</h3>
                            <span class="bg-teal-100 text-teal-800 text-xs px-2 py-1 rounded-full">Duração: 6h</span>
                        </div>
                        <p class="text-gray-600 mb-4">Conheça a história, arquitetura e gastronomia da nossa cidade.</p>
                        <div class="flex items-center text-gray-500 mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Centro Histórico</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-teal-600">R$ 120</span>
                            <button class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-full transition duration-300">Reservar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <button class="border-2 border-teal-500 text-teal-500 hover:bg-teal-500 hover:text-white px-6 py-3 rounded-full font-semibold transition duration-300">Ver Todos os Passeios</button>
            </div>
        </div>
    </section> --}}

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center">
                @if($site->sobre_imagem)
                    <div class="md:w-1/2 mb-10 md:mb-0">
                        <img src="{{ asset('storage/' . $site->sobre_imagem) }}" alt="Sobre nós" class="rounded-lg shadow-xl w-full">
                    </div>
                @endif
    
                <div class="md:w-1/2 md:pl-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">{{ $site->sobre_titulo ?? 'Sobre Nós' }}</h2>
                    <p class="text-gray-600 mb-6">{{ $site->sobre_descricao }}</p>
    
                    <div class="mb-6">
                        @foreach($site->sobre_itens ?? [] as $item)
                            <div class="flex items-start mb-4">
                                <div class="bg-teal-100 p-3 rounded-full mr-4">
                                    <i class="fas {{ $item['icone'] ?? 'fa-star' }} text-teal-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-1">{{ $item['titulo'] }}</h3>
                                    <p class="text-gray-600">{{ $item['descricao'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    <!-- Testimonials -->
    <section class="py-20 bg-gray-100">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">O Que Dizem Nossos Clientes</h2>
            <div class="w-20 h-1 bg-teal-500 mx-auto mb-6"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($site->depoimentos as $depoimento)
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <img src="{{ $depoimento->foto ? asset('storage/' . $depoimento->foto) : 'https://via.placeholder.com/50' }}" alt="{{ $depoimento->nome }}" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-semibold">{{ $depoimento->nome }}</h4>
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
                </div>
            @endforeach
        </div>
    </div>
</section>


    <!-- Contact Section 
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Entre em Contato</h2>
                <div class="w-20 h-1 bg-teal-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Tem dúvidas ou quer fazer uma reserva? Mande uma mensagem que responderemos o mais rápido possível.</p>
            </div>
            
            <div class="max-w-4xl mx-auto bg-gray-100 rounded-xl shadow-lg overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/2 wave-bg text-white p-10">
                        <h3 class="text-2xl font-bold mb-6">Informações de Contato</h3>
                        <div class="flex items-start mb-6">
                            <i class="fas fa-map-marker-alt text-xl mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-semibold">Endereço</h4>
                                <p>Av. Beira Mar, 1234<br>Praia do Sol - SP</p>
                            </div>
                        </div>
                        <div class="flex items-start mb-6">
                            <i class="fas fa-phone-alt text-xl mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-semibold">Telefone</h4>
                                <p>(11) 9999-9999</p>
                            </div>
                        </div>
                        <div class="flex items-start mb-6">
                            <i class="fas fa-envelope text-xl mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-semibold">Email</h4>
                                <p>contato@{{ $site->titulo }}.com.br</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-clock text-xl mr-4 mt-1"></i>
                            <div>
                                <h4 class="font-semibold">Horário de Funcionamento</h4>
                                <p>Segunda a Sábado: 8h às 18h</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-1/2 p-10">
                        <form>
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Nome</label>
                                <input type="text" id="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div class="mb-4">
                                <label for="phone" class="block text-gray-700 font-medium mb-2">Telefone</label>
                                <input type="tel" id="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            </div>
                            <div class="mb-4">
                                <label for="message" class="block text-gray-700 font-medium mb-2">Mensagem</label>
                                <textarea id="message" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 rounded-lg transition duration-300">Enviar Mensagem</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <!-- Footer -->
    <footer class="wave-bg text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ $site->titulo }}</h3>
                    <p class="mb-4">Escola de surf e passeios turísticos na Praia do Sol.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-teal-200"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white hover:text-teal-200"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white hover:text-teal-200"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white hover:text-teal-200"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="#home" class="hover:text-teal-200">Início</a></li>
                        <li><a href="#surf" class="hover:text-teal-200">Aulas de Surf</a></li>
                        <li><a href="#tours" class="hover:text-teal-200">Passeios</a></li>
                        <li><a href="#about" class="hover:text-teal-200">Sobre Nós</a></li>
                        <li><a href="#contact" class="hover:text-teal-200">Contato</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Horários</h3>
                    <ul class="space-y-2">
                        <li>Segunda a Sexta: 8h - 18h</li>
                        <li>Sábado: 8h - 14h</li>
                        <li>Domingo: Fechado</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="mb-4">Inscreva-se para receber promoções e novidades.</p>
                    <div class="flex">
                        <input type="email" placeholder="Seu email" class="px-4 py-2 rounded-l-lg focus:outline-none text-gray-800 w-full">
                        <button class="bg-teal-700 hover:bg-teal-800 px-4 py-2 rounded-r-lg"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <div class="border-t border-teal-400 mt-8 pt-8 text-center">
                <p>&copy; 2023 {{ $site->titulo }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Simple JavaScript for mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.md\\:hidden');
            const navLinks = document.querySelector('.hidden.md\\:flex');
            
            mobileMenuButton.addEventListener('click', function() {
                navLinks.classList.toggle('hidden');
                navLinks.classList.toggle('flex');
                navLinks.classList.toggle('flex-col');
                navLinks.classList.toggle('absolute');
                navLinks.classList.toggle('top-16');
                navLinks.classList.toggle('left-0');
                navLinks.classList.toggle('right-0');
                navLinks.classList.toggle('bg-teal-600');
                navLinks.classList.toggle('p-4');
                navLinks.classList.toggle('space-y-4');
                navLinks.classList.toggle('space-x-8');
            });
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    if(this.getAttribute('href') === '#') return;
                    
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if(!navLinks.classList.contains('hidden')) {
                        mobileMenuButton.click();
                    }
                });
            });
        });
    </script>
</body>
</html>