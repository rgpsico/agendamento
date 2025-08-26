<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Mobile Corrigida</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <style>
        /* Modern Sidebar Styles - Beach Sports Theme */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #e0f2fe 0%, #b3e5fc 50%, #81d4fa 100%);
            border: none;
            box-shadow: 4px 0 20px rgba(0, 150, 199, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: fixed;
            height: 100vh;
            overflow: hidden;
            z-index: 1000;
            top: 0;
            left: 0;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #00bcd4, #4fc3f7, #81d4fa, #ffcc02, #ff9800);
        }

        .sidebar-inner {
            height: 100%;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .sidebar-inner::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-inner::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-inner::-webkit-scrollbar-thumb {
            background: rgba(0, 188, 212, 0.3);
            border-radius: 3px;
        }

        .sidebar-inner::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 188, 212, 0.5);
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* Menu Title */
        .menu-title {
            padding: 15px 25px 10px;
            margin-bottom: 5px;
            position: relative;
            opacity: 0;
            transform: translateX(-20px);
        }

        .menu-title span {
            color: rgba(9, 12, 14, 0.8);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Inter', sans-serif;
        }

        .menu-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25px;
            right: 25px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 188, 212, 0.3), transparent);
        }

        /* Menu Items */
        .sidebar-menu li {
            margin: 2px 10px;
            opacity: 0;
            transform: translateX(-30px);
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(9, 12, 14, 0.8);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-menu li a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .sidebar-menu li a:hover::before {
            left: 100%;
        }

        .sidebar-menu li a:hover {
            color: rgba(0, 77, 105, 1);
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 188, 212, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .sidebar-menu li a.active {
            background: linear-gradient(135deg, #00bcd4, #4fc3f7);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 188, 212, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar-menu li a i {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 16px;
            transition: transform 0.3s ease;
            color: rgba(9, 12, 14, 0.8);
        }

        .sidebar-menu li a:hover i {
            transform: scale(1.1);
        }

        .sidebar-menu li a span {
            flex: 1;
            font-size: 14px;
            color: rgba(9, 12, 14, 0.8);
        }

        /* Menu Arrow */
        .menu-arrow {
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-arrow::after {
            content: '\f054';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 10px;
            color: rgba(0, 96, 128, 0.7);
        }

        /* Submenu */
        .submenu > ul {
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 5px 0;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .submenu.active > ul {
            max-height: 500px;
            padding: 10px 0;
        }

        .submenu.active .menu-arrow {
            transform: rotate(90deg);
        }

        .submenu ul li {
            margin: 1px 0;
        }

        .submenu ul li a {
            padding: 8px 20px 8px 45px;
            font-size: 13px;
            background: transparent;
            position: relative;
        }

        .submenu ul li a::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 50%;
            width: 4px;
            height: 4px;
            background: rgba(0, 188, 212, 0.6);
            border-radius: 50%;
            transform: translateY(-50%);
            transition: all 0.3s ease;
        }

        .submenu ul li a:hover::before {
            background: #ff9800;
            transform: translateY(-50%) scale(1.5);
            box-shadow: 0 0 8px rgba(255, 152, 0, 0.5);
        }

        .submenu ul li a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(10px);
            color: rgba(0, 77, 105, 1);
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: fixed !important;
            top: 20px !important;
            left: 20px !important;
            background: #00bcd4 !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            z-index: 1100 !important;
            font-size: 18px !important;
            box-shadow: 0 4px 15px rgba(0, 188, 212, 0.3) !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
        }

        .sidebar-toggle:hover {
            background: #0097a7 !important;
            transform: scale(1.05) !important;
            box-shadow: 0 6px 20px rgba(0, 188, 212, 0.4) !important;
        }

        /* Overlay para fechar o menu no mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Content area */
        .content {
            margin-left: 280px;
            padding: 20px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-active {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block !important;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none !important;
            }
        }

        /* Loading Animation */
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: calc(200px + 100%) 0; }
        }

        .loading-item {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            background-size: 200px 100%;
            animation: shimmer 1.5s infinite;
        }

        /* Icon Animations */
        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .sidebar-menu li a.pulse-icon i {
            animation: iconPulse 2s infinite;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #ff6b35, #f7931e);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
            min-width: 16px;
            text-align: center;
            animation: pulse 2s infinite;
            box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
    </style>
</head>

<body>
    <!-- Botão Toggle para Mobile -->

    <!-- Overlay para fechar o menu no mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <!-- Seção: Menu -->
                    <li class="menu-title"><span>Menu</span></li>

                    <!-- Dashboard -->
                    <li class="menu-item" data-delay="0.1">
                        <a href="#dashboard" class="pulse-icon">
                            <i class="fe fe-home"></i> 
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Site Templates -->
                    <li class="submenu menu-item" data-delay="0.3">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-layer-group"></i>
                            <span>Site Templates</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#templates-list">Lista</a></li>                            
                        </ul>
                    </li>

                    <!-- Serviços -->
                    <li class="submenu menu-item" data-delay="0.4">
                        <a href="#" class="submenu-toggle">
                            <i class="fe fe-activity"></i>
                            <span>Serviços</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#services-list">Listar Serviços</a></li>
                        </ul>
                    </li>

                    <!-- Horários -->
                    <li class="submenu menu-item" data-delay="0.5">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-clock"></i>
                            <span>Horários</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#schedule-unique">Únicos</a></li>
                            <li><a href="#schedule-custom">Personalizado</a></li>
                            <li><a href="#schedule-auto">Automáticos</a></li>
                        </ul>
                    </li>

                    <!-- Site -->
                    <li class="submenu menu-item" data-delay="0.2">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-globe"></i>
                            <span>Site</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#site-list">Lista</a></li>
                            <li><a href="#site-config">Configurações do Site</a></li>
                            <li><a href="#site-services">Serviços</a></li>
                            <li><a href="#site-testimonials">Depoimentos</a></li>
                            <li><a href="#site-contacts">Contatos</a></li>
                        </ul>
                    </li>

                    <!-- Alunos -->
                    <li class="menu-item" data-delay="0.6">
                        <a href="#students">
                            <i class="fe fe-users"></i>
                            <span>Alunos</span>
                        </a>
                    </li>

                    <!-- Agenda -->
                    <li class="submenu menu-item" data-delay="0.7">
                        <a href="#" class="submenu-toggle">
                            <i class="fe fe-calendar"></i>
                            <span>Agenda</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#agenda-list">Listar Aulas</a></li>
                            <li><a href="#agenda-calendar">Calendário</a></li>
                        </ul>
                    </li>

                    <!-- Fotos -->
                    <li class="menu-item" data-delay="0.8">
                        <a href="#photos">
                            <i class="fe fe-camera"></i>
                            <span>Fotos</span>
                        </a>
                    </li>

                    <!-- Dados Cadastrais -->
                    <li class="submenu menu-item" data-delay="0.9">
                        <a href="#" class="submenu-toggle">
                            <i class="fe fe-file"></i>
                            <span>Dados Cadastrais</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#company-data">Empresa</a></li>
                            <li><a href="#company-address">Endereço</a></li>
                            <li><a href="#system-general">Sistema Geral</a></li>
                        </ul>
                    </li>

                    <!-- Pagamentos -->
                    <li class="submenu menu-item" data-delay="1.0">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-credit-card"></i>
                            <span>Pagamentos</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#payments-list">Listar</a></li>
                            <li><a href="#payments-create">Cadastrar</a></li>
                            <li><a href="#payments-config">Configurações</a></li>
                        </ul>
                    </li>

                    <!-- Integrações -->
                    <li class="submenu menu-item" data-delay="1.1">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-plug"></i>
                            <span>Integrações</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#integration-asaas">Asaas</a></li>
                            <li><a href="#integration-pix">PIX</a></li>
                            <li><a href="#integration-stripe">Stripe</a></li>
                            <li><a href="#integration-mercadopago">Mercado Pago</a></li>
                        </ul>
                    </li>

                    <!-- Empresas -->
                    <li class="submenu menu-item" data-delay="1.2">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-building"></i>
                            <span>Empresas</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#companies-list">Listar</a></li>
                            <li><a href="#companies-create">Cadastrar</a></li>
                        </ul>
                    </li>

                    <!-- Modalidades -->
                    <li class="submenu menu-item" data-delay="1.3">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-swimmer"></i>
                            <span>Modalidades</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#modalities-list">Listar</a></li>
                            <li><a href="#modalities-create">Cadastrar</a></li>
                        </ul>
                    </li>

                    <!-- Usuários -->
                    <li class="submenu menu-item" data-delay="1.4">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-user-cog"></i>
                            <span>Usuários</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#users-list">Usuários</a></li>           
                            <li><a href="#permissions">Permissões</a></li>                            
                        </ul>
                    </li>
                    
                    <!-- Configurações -->
                    <li class="submenu menu-item" data-delay="1.5">
                        <a href="#" class="submenu-toggle">
                            <i class="fas fa-cogs"></i>
                            <span>Configurações</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="#config-permissions">Permissões</a></li>
                            <li><a href="#config-payments">Pagamentos</a></li>
                            <li><a href="#config-company">Empresa</a></li>
                            <li><a href="#config-users">Usuários</a></li>
                            <li><a href="#config-system">Sistema</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <script>
        // Enhanced Sidebar Animations with GSAP
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');

            // Função para abrir/fechar sidebar no mobile
            function toggleSidebar() {
                sidebar.classList.toggle('mobile-active');
                sidebarOverlay.classList.toggle('active');
                
                // Animar o ícone do botão
                const icon = sidebarToggle.querySelector('i');
                if (sidebar.classList.contains('mobile-active')) {
                    gsap.to(icon, { rotation: 90, duration: 0.3 });
                } else {
                    gsap.to(icon, { rotation: 0, duration: 0.3 });
                }
            }

            // Função para fechar sidebar
            function closeSidebar() {
                sidebar.classList.remove('mobile-active');
                sidebarOverlay.classList.remove('active');
                
                const icon = sidebarToggle.querySelector('i');
                gsap.to(icon, { rotation: 0, duration: 0.3 });
            }

            // Event Listeners
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleSidebar();
            });

            // Fechar ao clicar no overlay
            sidebarOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                closeSidebar();
            });

            // Fechar ao clicar em um link do menu no mobile
            const sidebarLinks = sidebar.querySelectorAll('a:not(.submenu-toggle)');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });

            // Fechar ao redimensionar para desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });

            // GSAP Timeline for sidebar animations
            const tl = gsap.timeline();

            // Animate menu title
            tl.fromTo('.menu-title', 
                { opacity: 0, x: -20 },
                { opacity: 1, x: 0, duration: 0.6, ease: "power2.out" }
            );

            // Animate menu items with stagger
            tl.fromTo('.menu-item', 
                { opacity: 0, x: -30 },
                { 
                    opacity: 1, 
                    x: 0, 
                    duration: 0.5, 
                    stagger: 0.1,
                    ease: "back.out(1.7)"
                },
                "-=0.3"
            );

            // Submenu functionality with GSAP
            $('.submenu-toggle').on('click', function(e) {
                e.preventDefault();
                const $submenu = $(this).closest('.submenu');
                const $submenuList = $submenu.find('ul');
                const isActive = $submenu.hasClass('active');

                // Close other submenus
                $('.submenu.active').not($submenu).each(function() {
                    $(this).removeClass('active');
                    gsap.to($(this).find('ul')[0], {
                        maxHeight: 0,
                        duration: 0.3,
                        ease: "power2.inOut"
                    });
                });

                if (!isActive) {
                    $submenu.addClass('active');
                    
                    // Animate submenu opening
                    gsap.fromTo($submenuList[0], 
                        { maxHeight: 0, opacity: 0 },
                        { 
                            maxHeight: 500, 
                            opacity: 1,
                            duration: 0.4, 
                            ease: "power2.out" 
                        }
                    );

                    // Animate submenu items
                    gsap.fromTo($submenuList.find('li'), 
                        { opacity: 0, x: -20 },
                        { 
                            opacity: 1, 
                            x: 0, 
                            duration: 0.3, 
                            stagger: 0.05,
                            delay: 0.1,
                            ease: "power2.out" 
                        }
                    );
                } else {
                    $submenu.removeClass('active');
                    gsap.to($submenuList[0], {
                        maxHeight: 0,
                        opacity: 0,
                        duration: 0.3,
                        ease: "power2.inOut"
                    });
                }
            });

            // Active menu item detection
            const currentUrl = window.location.pathname;
            $('.sidebar-menu a').each(function() {
                if ($(this).attr('href') === currentUrl) {
                    $(this).addClass('active');
                    
                    // If it's in a submenu, open the parent
                    const $parentSubmenu = $(this).closest('.submenu');
                    if ($parentSubmenu.length) {
                        $parentSubmenu.addClass('active');
                    }
                }
            });

            // Hover effects with GSAP
            $('.sidebar-menu li a').hover(
                function() {
                    gsap.to($(this).find('i')[0], {
                        rotation: 360,
                        duration: 0.5,
                        ease: "power2.out"
                    });
                },
                function() {
                    gsap.to($(this).find('i')[0], {
                        rotation: 0,
                        duration: 0.3,
                        ease: "power2.out"
                    });
                }
            );

            // Notification animation
            gsap.to('.notification-badge', {
                scale: 1.2,
                duration: 0.8,
                yoyo: true,
                repeat: -1,
                ease: "power2.inOut"
            });
        });
    </script>
