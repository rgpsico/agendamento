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