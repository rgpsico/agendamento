<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (!empty($empresaId))
        <meta name="empresa-id" content="{{ $empresaId }}">
    @endif

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
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <!-- Google tag (gtag.js) -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-4ZMP2C63TR"> --}}
    </script>
    {{-- <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-4ZMP2C63TR');
  </script> --}}
</head>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <header class="header header-fixed header-one">
            <div class="container">
                <nav class="navbar navbar-expand-lg header-nav">
                    <div class="navbar-header">
                        <!-- Botão Mobile -->
                        <a id="mobile_btn" href="{{ route('home.index') }}">
                            <span class="bar-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </a>
                        <!-- Logo -->
                        <a href="{{ route('home.index') }}" class="navbar-brand logo">
                            <img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png"
                                class="img-fluid" alt="Logo">
                        </a>
                    </div>

                    <div class="main-menu-wrapper">
                        <div class="menu-header">
                            <a href="" class="menu-logo">
                                <img src="https://rjpasseios.com.br/wp-content/uploads/2024/12/cropped-logo-1.png"
                                    class="img-fluid" alt="Logo">
                            </a>
                            <a id="menu_close" class="menu-close" href="javascript:void(0);">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>

                        <!-- Menu -->
                        <ul class="main-nav d-flex align-items-center">
                            @if (auth()->check())
                                <li class="nav-item me-3">
                                    <span class="text-dark fw-bold" style="color:#000;">Bem-vindo,
                                        {{ auth()->user()->nome }}!</span>
                                </li>

                                @if (auth()->user()->tipo_usuario == 'Professor')
                                    <li class="nav-item" style="color:#000;">
                                        <a href="{{ route('cliente.dashboard') }}" class="btn btn-outline-primary"
                                            style="color:#000;">
                                            <i class="fas fa-user-cog"></i> Admin
                                        </a>
                                    </li>
                                @elseif(auth()->user()->tipo_usuario == 'Aluno')
                                    <li class="nav-item">
                                        <a href="{{ route('alunos.aulas') }}" class="btn btn-outline-primary"
                                            style="color:#000;">
                                            <i class="fas fa-book"></i> Minhas Aulas
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->isAdmin)
                                    <li class="nav-item">
                                        <a href="{{ route('admin.analytics.dashboard') }}"
                                            class="btn btn-outline-secondary">
                                            <i class="fas fa-chart-bar"></i> Relatórios
                                        </a>
                                    </li>
                                @endif

                                <li class="nav-item ms-2" style="color:#000;">
                                    <a href="{{ route('user.logout') }}" class="btn btn-outline-danger"
                                        style="color:#000;">
                                        <i class="fas fa-sign-out-alt"></i> Sair

                                    </a>
                                </li>
                            @else
                                {{-- Caso não haja usuário autenticado, mostre as opções de login --}}
                                <li class="nav-item">
                                    <a href="{{ route('home.login') }}" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt"></i> Login
                                    </a>
                                </li>

                                <li class="nav-item" id="">
                                    <a href="" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#registerModal">
                                        <i class="fas fa-sign-in-alt"></i> Registrar-se
                                    </a>
                                </li>
                            @endif
                        </ul>

                    </div>
                </nav>
            </div>
        </header>
        <!-- /Header -->
    </div>

    <!-- Scripts -->

    <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/analytics.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validação de confirmação de senha
            try {


                function validatePasswordMatch(passwordId, confirmPasswordId) {
                    const password = document.getElementById(passwordId);
                    const confirmPassword = document.getElementById(confirmPasswordId);

                    if (!password || !confirmPassword) return; // ← evita o erro

                    function checkMatch() {
                        if (password.value !== confirmPassword.value) {
                            confirmPassword.setCustomValidity('As senhas não coincidem');
                        } else {
                            confirmPassword.setCustomValidity('');
                        }
                    }

                    password.addEventListener('input', checkMatch);
                    confirmPassword.addEventListener('input', checkMatch);
                }


                // Aplicar validação para ambos os formulários
                validatePasswordMatch('alunoSenha', 'alunoConfirmarSenha');
                validatePasswordMatch('professorSenha', 'professorConfirmarSenha');

                // Handlers para os formulários (para demonstração)
                document.getElementById('alunoForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Cadastro de aluno enviado! (Demonstração)');
                    // Aqui você colocaria a lógica real de envio
                });

                document.getElementById('professorForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Cadastro de professor enviado! (Demonstração)');
                    // Aqui você colocaria a lógica real de envio
                });

                // Log quando o modal for aberto
                const registerModal = document.getElementById('registerModal');
                registerModal.addEventListener('show.bs.modal', function() {
                    console.log('Modal de registro está sendo exibido.');
                });
            } catch (error) {
                console.log('aa')
            }
        });
    </script>
</body>

</html>
