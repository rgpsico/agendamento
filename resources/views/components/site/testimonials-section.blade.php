@php
$depoimentos = $site->depoimentos ?? collect([]);

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
            @if($depoimentos->isEmpty())
                @foreach($fakeTestimonials as $depoimento)           
                    <x-site.testimonial-card :depoimento="$depoimento" :fake="true" />
                @endforeach
            @else
                @foreach($depoimentos as $depoimento)
                    <x-site.testimonial-card :depoimento="$depoimento" :fake="false" />
                @endforeach
            @endif
        </div>
    </div>
</section>
