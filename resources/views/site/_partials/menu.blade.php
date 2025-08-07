<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center nav-logo">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-water text-2xl service-icon"></i>
                    </div>
                    <span class="text-white text-2xl font-bold">{{ $site->titulo }}</span>
                </div>
                <div class="hidden md:flex space-x-8 nav-menu">
                    <a href="#home" class="nav-link text-white hover:text-gray-200">Início</a>
                    <a href="#services" class="nav-link text-white hover:text-gray-200">Serviços</a>
                    <a href="#about" class="nav-link text-white hover:text-gray-200">Sobre</a>
                    <a href="#testimonials" class="nav-link text-white hover:text-gray-200">Depoimentos</a>
                    <a href="#contact" class="nav-link text-white hover:text-gray-200">Contato</a>
                </div>
                <button class="md:hidden focus:outline-none mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars text-white text-2xl"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="fixed inset-0 z-40 transform -translate-x-full transition-transform duration-300 md:hidden" id="mobileMenu">
        <div class="glass-card h-full w-64 p-6">
            <div class="flex justify-end mb-8">
                <button class="text-white" id="closeMobileMenu">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <div class="space-y-6">
                <a href="#home" class="block text-white text-lg mobile-nav-link">Início</a>
                <a href="#services" class="block text-white text-lg mobile-nav-link">Serviços</a>
                <a href="#about" class="block text-white text-lg mobile-nav-link">Sobre</a>
                <a href="#testimonials" class="block text-white text-lg mobile-nav-link">Depoimentos</a>
                <a href="#contact" class="block text-white text-lg mobile-nav-link">Contato</a>
            </div>
        </div>
    </div>