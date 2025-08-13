@php
$imagemFundo = $site->sobre_imagem
    ? asset('storage/' . $site->sobre_imagem)
    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';

$sobreItens = $site->sobre_itens ?? [
    ['icone' => 'fa-award', 'titulo' => 'Instrutores Certificados', 'descricao' => 'Todos nossos instrutores possuem certificação internacional e anos de experiência.'],
    ['icone' => 'fa-leaf', 'titulo' => 'Sustentabilidade', 'descricao' => 'Comprometidos com a preservação do meio ambiente e oceanos limpos.'],
    ['icone' => 'fa-heart', 'titulo' => 'Comunidade', 'descricao' => 'Mais que uma escola, somos uma família de amantes do surf.']
];

$sobreImagens = $site->sobre_imagens ?? [
    'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
    'https://images.unsplash.com/photo-1502680390469-be75c86b636f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
    'https://images.unsplash.com/photo-1530549387789-4c1017266635?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80',
    'https://images.unsplash.com/photo-1537519646099-335112b4e681?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
];
@endphp

<section id="about" class="py-20 bg-white parallax-bg" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('{{ $imagemFundo }}');">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="about-content">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 about-title">{{ $site->sobre_titulo ?? 'Por que escolher a OceanWave?' }}</h2>
                <p class="text-gray-200 text-lg mb-8 about-description">{{ $site->sobre_descricao ?? 'Com mais de 15 anos de experiência, somos a escola de surf líder na região.' }}</p>
                
                <div class="space-y-6 about-features">
                    @foreach($sobreItens as $item)
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
                    @foreach($sobreImagens as $index => $imagem)
                        <img src="{{ $imagem }}" alt="Imagem sobre" class="rounded-lg shadow-lg about-image-item" data-index="{{ $index }}">
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
