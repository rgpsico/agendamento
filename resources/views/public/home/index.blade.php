<x-public.layout title="HOME">


    <!-- Breadcrumb -->

    @include('public.home._partials.modalregister')
    <x-home.breadcrumb title="Busque Seu Passeio " />
    <!-- /Breadcrumb -->

    <!-- Page Content -->
    <style>
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .modal-header {
            background: transparent;
            border: none;
            padding: 2rem 2rem 1rem;
            position: relative;
        }

        .modal-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            width: 100%;
            margin: 0;
        }

        .btn-close {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            opacity: 1;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .btn-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
            transition: all 0.3s ease;
        }

        .modal-body {
            background: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
        }

        .welcome-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            position: relative;
            animation: pulse 2s infinite;
        }

        .welcome-icon i {
            font-size: 3rem;
            color: white;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .welcome-text {
            margin-bottom: 2rem;
        }

        .welcome-text h6 {
            color: #333;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .welcome-text p {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 0.8rem;
        }

        .features-list {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 2rem 0;
        }

        .feature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 15px;
            min-width: 120px;
            transition: transform 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-5px);
        }

        .feature-item i {
            font-size: 1.5rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .feature-item span {
            font-size: 0.9rem;
            color: #555;
            font-weight: 500;
        }

        .modal-footer {
            background: white;
            border: none;
            padding: 1rem 2rem 2rem;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }

        .modal-dialog {
            max-width: 500px;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 1rem;
            }

            .features-list {
                justify-content: center;
            }

            .feature-item {
                min-width: 100px;
                padding: 0.8rem;
            }
        }

        .decorative-element {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            opacity: 0.5;
        }

        .decorative-element::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
    </style>


    <div class="modal fade" id="modalBoasVindas" aria-labelledby="modalBoasVindasLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="decorative-element"></div>
                    <h5 class="modal-title" id="modalBoasVindasLabel">
                        <i class="fas fa-sparkles me-2"></i>Bem-vindo(a)!
                    </h5>
                    <button type="button" class="btn-close" id="fecharModalBoasVindas" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="welcome-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>

                    <div class="welcome-text">
                        <h6>Que bom ter você aqui!</h6>
                        <p>Estamos muito felizes em recebê-lo em nosso sistema de agendamento.</p>
                        <p>Descubra todas as funcionalidades disponíveis para você:</p>
                    </div>

                    <div class="features-list">
                        <div class="feature-item">
                            <i class="fas fa-search"></i>
                            <span>Explorar</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-filter"></i>
                            <span>Filtrar</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Agendar</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="fas fa-rocket me-2"></i>Começar Agora
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('public.home._partials.filterhome')
    
    @include('public.home._partials.batepapo')
    <!-- /Page Content -->
    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>

    <script>
        $(document).ready(function() {
            if (!sessionStorage.getItem('modalBoasVindasMostrado')) {
                $('#modalBoasVindas').modal('show');
                sessionStorage.setItem('modalBoasVindasMostrado', 'true');
            }
        });
    </script>


    </x-layoutsadmin>
