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
                            <img src="{{ $config->logo_header ? asset('storage/'.$config->logo_header) : 'https://via.placeholder.com/150x50?text=Logo' }}"
                                class="img-fluid" alt="Logo">
                        </a>
                    </div>

                    <div class="main-menu-wrapper">
                        <div class="menu-header">
                            <a href="" class="menu-logo">
                               <img src="{{ $config->logo_header ? asset('storage/'.$config->logo_header) : 'https://via.placeholder.com/150x50?text=Logo' }}"
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
