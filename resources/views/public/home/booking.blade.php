<x-public.layout title="HOME">
    <!-- Breadcrumb -->
    <x-home.breadcrumb title="AGENDAMENTO" />
    <!-- /Breadcrumb -->
<style>
    .chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 350px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    overflow: hidden;
}

.chatbot-container.minimized {
    height: 50px;
}

.chatbot-header {
    background: #007bff;
    color: #fff;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-header h5 {
    margin: 0;
    font-size: 16px;
}

.chatbot-toggle {
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
}

.chatbot-messages {
    max-height: 300px;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
}

.message {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 8px;
    max-width: 80%;
}

.user-message {
    background: #007bff;
    color: #fff;
    margin-left: auto;
    text-align: right;
}

.bot-message {
    background: #e9ecef;
    color: #333;
    margin-right: auto;
}

.chatbot-input {
    display: flex;
    border-top: 1px solid #ddd;
    padding: 10px;
}

.chatbot-input input {
    flex: 1;
    border: none;
    padding: 8px;
    border-radius: 5px;
    outline: none;
}

.chatbot-input button {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
}

.chatbot-input button:hover {
    background: #0056b3;
}
</style>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/booking.css') }}">

    

    @include('admin.empresas._partials.modal')

    <input type="hidden" id="professor_id" value="{{ $professor_id }}">

    <div class="main-booking-container">
        <!-- Profile Section -->
        <div class="profile-section">
            <div class="container">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <div class="booking-doc-info d-flex flex-column flex-md-row align-items-center">
                            <a href="" class="booking-doc-img mb-3 mb-md-0 me-md-4">
                                @isset($model->avatar)
                                    <img src="{{ asset('avatar/' . $model->avatar) }}" class="img-fluid usuario-img"
                                        alt="Usuario Image">
                                @else
                                    <img src="https://fastly.picsum.photos/id/553/536/354.jpg?hmac=_eKSdchYCZH98R5ND5JJCACG421CpRVSdNfRH3sMmpY"
                                        class="img-fluid usuario-img" alt="Usuario Image">
                                @endisset
                            </a>
                            <div class="booking-info text-center text-md-start">
                                <h4><a href="">{{ $model->nome ?? 'Sem Nome' }}</a></h4>
                                @isset($model)
                                    <x-avaliacao-home :model="$model" />
                                @endisset
                                <p class="text-muted mb-0 mt-2">
                                    <i class="fas fa-map-marker-alt me-2"></i> {{ $model->endereco->cidade ?? '' }},
                                    {{ $model->endereco->pais ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="services-section">
            <div class="container">
                <h3 class="services-title">Escolha seu Serviço</h3>
                <div class="row mb-4">
                    @isset($model->servicos)
                        @foreach ($model->servicos as $serv)
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="selection-wrapper card_servicos" data-servico_preco="{{ $serv->preco }}"
                                    data-servico_id="{{ $serv->id }}" data-servico_titulo="{{ $serv->titulo }}"
                                    data-tipo_agendamento="{{ $serv->tipo_agendamento }}">
                                    <label for="selected-item-{{ $serv->id }}" class="selected-label">
                                        <input type="radio" name="selected-item" id="selected-item-{{ $serv->id }}"
                                            class="servico-selecionado" data-servico_id="{{ $serv->id }}">
                                        <span class="icon"></span>
                                        <div class="selected-content">
                                            <img class="card-img-top img-fluid"
                                                src="{{ asset('servico/' . ($serv->imagem ?? 'admin/img/doctors/Thumbs.db')) }}"
                                                alt="{{ $serv->titulo }}"
                                                onerror="this.src='https://fastly.picsum.photos/id/553/536/354.jpg?hmac=_eKSdchYCZH98R5ND5JJCACG421CpRVSdNfRH3sMmpY'">
                                            <h4>{{ $serv->titulo }}</h4>
                                            <h5>{{ $serv->descricao }}</h5>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>

        <!-- Schedule Section -->
        <div class="schedule-section">
            <div class="container">
                <h3 class="schedule-title">Escolha Data e Horário</h3>

                <div class="row">
                    <div class="col-12">
                        <div class="date-display">
                            <h4 class="mb-1">{{ \Carbon\Carbon::now()->locale('pt_BR')->format('d \d\e F \d\e Y') }}
                            </h4>
                            <p class="mb-1" style="color:#fff; text-transform:capitalize;">
                                {{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('dddd') }}</p>
                        </div>
                    </div>
                </div>

                <input type="hidden" class="dia_da_semana">
                <input type="hidden" class="data">
                <input type="hidden" class="hora_da_aula">

                <!-- Schedule Widget -->
                <div id="spinner" class="spinner-border text-primary" role="status" style="display:none;">
                    <span class="sr-only">Loading...</span>
                </div>

                <div class="card booking-schedule schedule-widget">
                    <!-- Schedule Header -->
                    <div class="schedule-header" style="display:none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="day-slot">
                                    <ul>
                                        <li class="left-arrow">
                                            <a href=""><i class="fa fa-chevron-left"></i></a>
                                        </li>
                                        <li class="right-arrow">
                                            <a href=""><i class="fa fa-chevron-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Schedule Header -->

                    <!-- Schedule Content -->
                    <div class="schedule-cont">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="time-slot">
                                    <ul class="clearfix"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Schedule Content -->
                </div>
                <!-- /Schedule Widget -->
            </div>
        </div>

        <!-- Submit Section -->
        <div class="submit-section">
            <div class="container">
                <div class="proceed-btn text-end mt-4">
                    @if (Auth::check())
                        <a href="{{ route('home.checkoutAuth', ['user_id' => $model->user_id]) }}"
                            class="btn btn-primary submit-btn disabled">
                            <i class="fas fa-calendar-check me-2"></i>Agendar e Pagar
                        </a>
                    @else
                        <a href="{{ route('home.checkout', ['id' => $model->user_id]) }}"
                            class="btn btn-primary submit-btn disabled">
                            <i class="fas fa-calendar-check me-2"></i>Agendar e Pagar
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
{{-- 
  <div class="chatbot-container">
        <div class="chatbot-header">
            <h5>Chat com Assistente</h5>
            <button class="chatbot-toggle"><i class="fas fa-times"></i></button>
        </div>
        <div class="chatbot-messages" id="chatbot-messages">
            <!-- Messages will be appended here -->
            <div class="message bot-message">Olá! Como posso ajudar com seu agendamento hoje? @if (!Auth::check()) Por favor, informe seu número de telefone para continuar. @endif</div>
        </div>
        <div class="chatbot-input">
            @if (!Auth::check())
                <input type="text" id="chatbot-phone" placeholder="Digite seu número de telefone..." />
            @endif
            <input type="text" id="chatbot-input" placeholder="Digite sua mensagem..." />
            <button id="chatbot-send"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div> --}}
    
    <x-batepapo />

    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('js/booking.js') }}"></script>
  
    <script>
        $(document).ready(function() {
            // Handle image errors
            $('.card-img-top').on('error', function() {
                $(this).attr('src',
                    'https://fastly.picsum.photos/id/553/536/354.jpg?hmac=_eKSdchYCZH98R5ND5JJCACG421CpRVSdNfRH3sMmpY'
                );
            });

            $('.usuario-img').on('error', function() {
                $(this).attr('src',
                    'https://fastly.picsum.photos/id/553/536/354.jpg?hmac=_eKSdchYCZH98R5ND5JJCACG421CpRVSdNfRH3sMmpY'
                );
            });

            // Enhance service card interactions
            $('.card_servicos').hover(
                function() {
                    $(this).find('.card-img-top').css('transform', 'scale(1.05)');
                },
                function() {
                    if (!$(this).find('input[type="radio"]').is(':checked')) {
                        $(this).find('.card-img-top').css('transform', 'scale(1)');
                    }
                }
            );

            // Add smooth scrolling to sections
            $('.servico-selecionado').on('change', function() {
                if ($(this).is(':checked')) {
                    $('html, body').animate({
                        scrollTop: $('.schedule-section').offset().top - 100
                    }, 800);
                }
            });

            // Add animation to time slots when they load
            $(document).on('DOMNodeInserted', '.time-slot ul li', function() {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                }).animate({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                }, 300);
            });
        });
    </script>

    <script>
       
    </script>
</x-public.layout>
