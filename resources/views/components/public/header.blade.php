<!DOCTYPE html> 
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <!-- Favicons -->
    <link href="{{ asset('template/assets/img/favicon.png') }}" rel="icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/feather.css') }}">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap-datetimepicker.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/select2/css/select2.min.css') }}">

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fancybox/jquery.fancybox.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/custom.css') }}">

    <!-- Custom Modal Styles -->
    <style>
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(45deg, #00c4e0, #007a99);
            color: white;
            border-bottom: none;
            padding: 20px;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 30px;
            background: #f8f9fa;
        }

        .nav-tabs {
            border-bottom: 2px solid #00c4e0;
        }

        .nav-tabs .nav-link {
            color: #007a99;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background: #00c4e0;
            color: white;
            border: none;
        }

        .nav-tabs .nav-link:hover {
            background: rgba(0, 196, 224, 0.1);
            color: #003b4d;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #00c4e0;
            box-shadow: 0 0 8px rgba(0, 196, 224, 0.3);
        }

        .btn-register {
            background: linear-gradient(45deg, #ff6f61, #ff3d00);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: linear-gradient(45deg, #ff3d00, #d32f2f);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 61, 0, 0.4);
        }

        .form-label {
            font-weight: 600;
            color: #003b4d;
        }

        .tab-content {
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 576px) {
            .modal-body {
                padding: 20px;
            }

            .modal-title {
                font-size: 1.2rem;
            }

            .nav-tabs .nav-link {
                padding: 8px 15px;
                font-size: 0.9rem;
            }

            .form-control {
                padding: 10px;
            }
        }

        .main-menu-wrapper {
    display: none;
}

.main-menu-wrapper.active {
    display: block;
}


        /* Ajustes no header para consistência */
        .header {
            background: linear-gradient(135deg, #00c4e0 0%, #007a99 50%, #003b4d 100%);
            box-shadow: 0 4px 20px rgba(0, 196, 224, 0.3);
            transition: all 0.3s ease;
        }

        .header.scrolled {
            background: rgba(0, 196, 224, 0.95);
            backdrop-filter: blur(10px);
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff6f61, #ff3d00);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #ff3d00, #d32f2f);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 61, 0, 0.4);
        }

        .btn-outline-primary, .btn-outline-danger {
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover, .btn-outline-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
    </style>
</head>
<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        
        <!-- Header -->
       <!-- Header -->
<header class="header header-fixed header-one">
    <div class="container">
        <nav class="navbar navbar-expand-lg header-nav">
            <div class="navbar-header">
                <!-- Botão Mobile -->
                <a id="mobile_btn" href="javascript:void(0);">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>
                <!-- Logo -->
                <a href="{{ route('home.index') }}" class="navbar-brand logo">
                    <img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png" class="img-fluid" alt="Logo">
                </a>
            </div>

            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <a href="{{ route('home.index') }}" class="menu-logo">
                        <img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png" class="img-fluid" alt="Logo">
                    </a>
                    <a id="menu_close" class="menu-close" href="javascript:void(0);">
                        <i class="fas fa-times"></i>
                    </a>
                </div>

                <!-- Menu -->
                <ul class="main-nav d-flex align-items-center">
                    @if(auth()->check()) 
                        <li class="nav-item me-3">
                            <span class="text-dark fw-bold" style="color:#000;">Bem-vindo, {{ auth()->user()->nome }}!</span>
                        </li>
                
                        @if(auth()->user()->tipo_usuario == 'Professor')
                            <li class="nav-item">
                                <a href="{{ route('cliente.dashboard') }}" class="btn btn-outline-primary" style="color:#000;">
                                    <i class="fas fa-user-cog"></i> Admin
                                </a>                                        
                            </li>
                        @elseif(auth()->user()->tipo_usuario == 'Aluno')    
                            <li class="nav-item">
                                <a href="{{ route('alunos.aulas') }}" class="btn btn-outline-primary" style="color:#000;">
                                    <i class="fas fa-book"></i> Minhas Aulas
                                </a>                                        
                            </li>
                        @endif
                
                        <li class="nav-item ms-2">
                            <a href="{{ route('user.logout') }}" class="btn btn-outline-danger" style="color:#000;">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </a>                                        
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('home.login') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Registre-se
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</header>
        <!-- /Header -->

        <!-- Register Modal -->
        
        <!-- /Register Modal -->
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin/js/jquery-3.6.4.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Script para efeito de scroll no header e controle do modal -->
    <script>
       $(document).ready(function() {

    // Verify if jQuery and Bootstrap are loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded.');
    }
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JavaScript is not loaded.');
    }

    // Header scroll effect
    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $('.header').addClass('scrolled');
        } else {
            $('.header').removeClass('scrolled');
        }
    });

    // Mobile menu control
    $('#mobile_btn').click(function(e) {
        e.preventDefault();
        $('.main-menu-wrapper').addClass('active'); // Add the active class to show the menu
    });

    $('#menu_close').click(function(e) {
        e.preventDefault();
        $('.main-menu-wrapper').removeClass('active'); // Remove the active class to hide the menu
    });

    // Close the menu when clicking outside (optional, for better UX)
    $(document).click(function(e) {
        if (!$(e.target).closest('.main-menu-wrapper, #mobile_btn').length) {
            $('.main-menu-wrapper').removeClass('active');
        }
    });

    // Prevent clicks inside the menu from closing it
    $('.main-menu-wrapper').click(function(e) {
        e.stopPropagation();
    });

    // Force modal to open (for testing)
    $('#registerModal').on('show.bs.modal', function() {
        console.log('Modal is being displayed.');
    });
});
    </script>

</body>
</html>