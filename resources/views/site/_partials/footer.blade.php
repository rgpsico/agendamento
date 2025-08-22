 <!-- Footer -->
    <footer class="hero-gradient text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-water text-2xl service-icon"></i>
                        </div>
                        <span class="text-2xl font-bold">{{ $site->titulo }}</span>
                    </div>
                    <p class="mb-4 opacity-90">{{ $site->footer_descricao ?? 'Transformando vidas através do surf há mais de 15 anos.' }}</p>
                    <div class="flex space-x-4">
                        @foreach($site->redes_sociais ?? [
                            ['icone' => 'fa-facebook-f', 'url' => '#'],
                            ['icone' => 'fa-instagram', 'url' => '#'],
                            ['icone' => 'fa-youtube', 'url' => '#'],
                            ['icone' => 'fa-tiktok', 'url' => '#']
                        ] as $rede)
                            <a href="{{ $rede['url'] }}" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition-all duration-300">
                                <i class="fab {{ $rede['icone'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Serviços</h3>
                    <ul class="space-y-2 opacity-90">
                        @foreach($site->servicos as $servico)
                            <li><a href="#services" class="hover:text-gray-200 transition-colors">{{ $servico->titulo }}</a></li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links Úteis</h3>
                    <ul class="space-y-2 opacity-90">
                        <li><a href="#about" class="hover:text-gray-200 transition-colors">Sobre Nós</a></li>
                        <li><a href="#testimonials" class="hover:text-gray-200 transition-colors">Depoimentos</a></li>
                        <li><a href="#contact" class="hover:text-gray-200 transition-colors">Contato</a></li>
                        <li><a href="#" class="hover:text-gray-200 transition-colors">Blog</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="mb-4 opacity-90">Receba dicas, promoções e novidades!</p>
                    <div class="flex">
                        <input type="email" placeholder="Seu email" class="px-4 py-2 rounded-l-lg focus:outline-none text-gray-800 flex-1">
                        <button class="bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-r-lg transition-all duration-300">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-white border-opacity-20 mt-8 pt-8 text-center opacity-90">
                <p>&copy; {{ now()->year }} {{ $site->titulo }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>

        
        // Register GSAP plugins
        gsap.registerPlugin(ScrollTrigger, TextPlugin);

        // Loading screen animation
        window.addEventListener('load', function() {
            gsap.to('#loading', {
                opacity: 0,
                duration: 1,
                ease: "power2.out",
                onComplete: function() {
                    document.getElementById('loading').style.display = 'none';
                    initAnimations();
                }
            });
        });

        function initAnimations() {
            // Hero section animations
            gsap.timeline()
                .from('.hero-content h1', {
                    opacity: 0,
                    y: 100,
                    duration: 1,
                    ease: "power3.out"
                })
                .from('.hero-subtitle', {
                    opacity: 0,
                    y: 50,
                    duration: 0.8,
                    ease: "power2.out"
                }, "-=0.5")
                .from('.hero-buttons button', {
                    opacity: 0,
                    y: 30,
                    duration: 0.6,
                    stagger: 0.2,
                    ease: "back.out(1.7)"
                }, "-=0.3")
                .from('.hero-image', {
                    opacity: 0,
                    x: 100,
                    duration: 1,
                    ease: "power2.out"
                }, "-=1");

            // Typing effect for hero title
            const words = ["Ondas", "Mar", "Vida"];
            let currentWord = 0;
            
            function typeWord() {
               gsap.fromTo('.typing-text', 
                    { text: "" }, 
                    { text: "Passeios Rio de Janeiro", duration: 3, ease: "none" }
                );
            }
            typeWord();

            // Navbar scroll effect
            gsap.to('#navbar', {
                scrollTrigger: {
                    trigger: '#navbar',
                    start: 'top top',
                    end: '+=100',
                    scrub: true
                },
                backgroundColor: 'rgba(102, 126, 234, 0.95)',
                backdropFilter: 'blur(10px)',
                ease: "power2.out"
            });

            // Counter animation
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                
                gsap.fromTo(counter, {
                    innerHTML: 0
                }, {
                    innerHTML: target,
                    duration: 2,
                    ease: "power2.out",
                    snap: { innerHTML: 1 },
                    scrollTrigger: {
                        trigger: counter,
                        start: 'top 80%'
                    },
                    onUpdate: function() {
                        counter.innerHTML = Math.ceil(counter.innerHTML);
                    }
                });
            });

            // Stagger animations for cards and sections
            gsap.utils.toArray('.stagger-item').forEach((item, index) => {
                gsap.from(item, {
                    opacity: 0,
                    y: 100,
                    duration: 0.8,
                    delay: index * 0.2,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: item,
                        start: 'top 85%'
                    }
                });
            });

            // Section headers animation
            gsap.utils.toArray('.section-header').forEach(header => {
                gsap.from(header.children, {
                    opacity: 0,
                    y: 50,
                    duration: 1,
                    stagger: 0.2,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: header,
                        start: 'top 80%'
                    }
                });
            });

            // About section features animation
            gsap.utils.toArray('.about-features > div').forEach((feature, index) => {
                gsap.from(feature, {
                    opacity: 0,
                    x: -100,
                    duration: 0.8,
                    delay: index * 0.2,
                    ease: "back.out(1.7)",
                    scrollTrigger: {
                        trigger: feature,
                        start: 'top 85%'
                    }
                });
            });

            // CTA section animation
            gsap.from('.cta-title', {
                opacity: 0,
                scale: 0.5,
                duration: 1,
                ease: "back.out(1.7)",
                scrollTrigger: {
                    trigger: '.cta-title',
                    start: 'top 80%'
                }
            });

            gsap.from('.cta-subtitle', {
                opacity: 0,
                y: 30,
                duration: 0.8,
                delay: 0.3,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: '.cta-subtitle',
                    start: 'top 85%'
                }
            });

            gsap.from('.cta-buttons button', {
                opacity: 0,
                y: 30,
                duration: 0.6,
                stagger: 0.2,
                delay: 0.5,
                ease: "back.out(1.7)",
                scrollTrigger: {
                    trigger: '.cta-buttons',
                    start: 'top 85%'
                }
            });

            // Parallax effect for about section
            gsap.to('#about', {
                backgroundPosition: '50% 100%',
                ease: 'none',
                scrollTrigger: {
                    trigger: '#about',
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true
                }
            });
        }

        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

        mobileMenuBtn.addEventListener('click', () => {
            gsap.to(mobileMenu, {
                x: 0,
                duration: 0.3,
                ease: "power2.out"
            });
        });

        closeMobileMenu.addEventListener('click', () => {
            gsap.to(mobileMenu, {
                x: '-100%',
                duration: 0.3,
                ease: "power2.out"
            });
        });

        mobileNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                gsap.to(mobileMenu, {
                    x: '-100%',
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });

        // Smooth scrolling for all anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                if(this.getAttribute('href') === '#') return;
                
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    gsap.to(window, {
                        duration: 1,
                        scrollTo: {
                            y: target,
                            offsetY: 80
                        },
                        ease: "power2.out"
                    });
                }
            });
        });

        // Add hover animations to service cards
        document.querySelectorAll('.card-hover').forEach(card => {
            card.addEventListener('mouseenter', () => {
                gsap.to(card, {
                    y: -15,
                    scale: 1.02,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            card.addEventListener('mouseleave', () => {
                gsap.to(card, {
                    y: 0,
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });

        // Enhanced About section animations
        gsap.timeline({
            scrollTrigger: {
                trigger: '#about',
                start: 'top 80%',
                end: 'bottom top',
                toggleActions: 'play none none none'
            }
        })
        .from('.about-title', {
            opacity: 0,
            y: 100,
            duration: 1,
            ease: 'power3.out'
        })
        .from('.about-description', {
            opacity: 0,
            y: 50,
            duration: 0.8,
            ease: 'power2.out'
        }, '-=0.5')
        .from('.about-feature', {
            opacity: 0,
            x: -100,
            rotation: -10,
            duration: 0.8,
            stagger: 0.2,
            ease: 'back.out(1.7)'
        }, '-=0.3')
        .from('.about-image-item', {
            opacity: 0,
            scale: 0.8,
            duration: 0.6,
            stagger: 0.2,
            ease: 'elastic.out(1, 0.5)'
        }, '-=0.5');

        // Enhanced parallax effect with zoom
        gsap.to('#about', {
            backgroundPosition: '50% 100%',
            scale: 1.1, // Adiciona um leve efeito de zoom
            ease: 'none',
            scrollTrigger: {
                trigger: '#about',
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });

        // Form submission animation
        document.querySelector('.contact-form form').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const button = e.target.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Enviado!';
                button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.style.background = '';
                    e.target.reset();
                }, 2000);
            }, 2000);
        });
    </script>
</body>
</html>