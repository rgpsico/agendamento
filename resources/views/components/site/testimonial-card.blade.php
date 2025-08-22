@props(['depoimento', 'fake' => false])

@php
$nota = $depoimento['nota'] ?? $depoimento->nota ?? 0;
$nome = $depoimento['nome'] ?? 'Aluno';
$comentario = $depoimento['comentario'] ?? '';
$servico = $depoimento['servico'] ?? ($depoimento->servico ?? 'Curso 2023');
$foto = $fake ? $depoimento['foto'] : ($depoimento->foto ? asset('storage/' . $depoimento->foto) : 'https://picsum.photos/50/50?random=' . rand(1,100));
@endphp

<div class="testimonial-card p-6 rounded-2xl shadow-lg ">
    <div class="flex items-center mb-4">
        <img src="{{ $foto }}" alt="{{ $nome }}" class="w-12 h-12 rounded-full mr-4">
        <div>
            <h4 class="font-semibold text-gray-800">{{ $nome }}</h4>
            <div class="flex text-yellow-400">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $nota)
                        <i class="fas fa-star"></i>
                    @elseif($i - $nota === 0.5)
                        <i class="fas fa-star-half-alt"></i>
                    @else
                        <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>
        </div>
    </div>
    <p class="text-gray-600 italic">"{{ $comentario }}"</p>
    <div class="mt-4 text-sm text-gray-500">
        <i class="fas fa-quote-left mr-2"></i>
        {{ $servico }}
    </div>
</div>
