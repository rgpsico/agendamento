
<section id="home" class="hero-gradient text-white min-h-screen flex items-center relative">
    <div class="wave-animation"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="hero-content">
                <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-6 text-glow">
                    <span class="typing-text">{{ $title }}</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90 hero-subtitle">
                    {{ $subtitle }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 hero-buttons">
                    @if($primaryButton)
                        <a href="{{ $primaryButton['link'] ?? '#' }}" 
                           class="btn-primary text-white px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all duration-300">
                            <i class="{{ $primaryButton['icon'] ?? '' }} mr-2"></i>
                            {{ $primaryButton['text'] }}
                        </a>
                    @endif

                    @if($secondaryButton)
                        <a href="{{ $secondaryButton['link'] ?? '#' }}" 
                           class="border-2 border-white text-white hover:bg-white hover:text-gray-800 px-8 py-4 rounded-full font-semibold text-lg transition-all duration-300">
                            <i class="{{ $secondaryButton['icon'] ?? '' }} mr-2"></i>
                            {{ $secondaryButton['text'] }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="hero-image">
                <div class="floating flex justify-center">
                    <img src="{{ $image ?? 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80' }}"  
                        alt="Hero Image" 
                        class="rounded-2xl shadow-2xl max-w-md w-full h-auto">
                </div>
            </div>

        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 scroll-indicator">
        <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
            <div class="w-1 h-3 bg-white rounded-full mt-2 animate-bounce"></div>
        </div>
    </div>
</section>
