<x-admin.layout title="Pagamento de Boleto">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 10%">
            <!-- Page Header -->
            <x-header.titulo pageTitle="Pagamento de Boleto" />
            <!-- /Page Header -->

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                              
                            </h4>
                        </div>

                        <input type="hidden" id="boleto-gerado-inicio" name="" value="">
                        <input type="hidden" id="boleto-gerado-fim" name="" value="">
                        <input type="hidden" id="user_id" name="" value="{{ Auth::user()->id }}">
                        <div class="card-body">
                            <x-alert />

                            @if (!Auth::user()->professor->asaas_customer_id)
                                <!-- Seção para criar Customer ID -->
                                <div class="alert alert-info border-0 shadow-sm mb-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="alert-heading">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Configuração Necessária
                                            </h5>
                                            <p class="mb-0">
                                                Para gerar seu boleto, precisamos criar sua identificação no sistema de
                                                pagamento.
                                                Este processo é rápido e seguro.
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button type="button" id="btn-criar-customer"
                                                class="btn btn-success btn-lg">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                Criar ID Asaas
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Loading spinner (inicialmente oculto) -->
                                <div id="loading-customer" class="text-center py-4" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Criando...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Criando sua identificação no sistema...</p>
                                </div>

                                <!-- Seção do boleto (inicialmente oculta) -->
                                <div id="boleto-section" style="display: none;">
                                @else
                                    <!-- Seção do boleto (visível quando já tem customer_id) -->
                                    <div id="boleto-section">
                            @endif

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="info-card">
                                        <label class="form-label text-muted">Empresa</label>
                                        <p class="fw-bold">{{ Auth::user()->empresa->nome }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-card">
                                        <label class="form-label text-muted">Cliente Asaas</label>
                                        <p class="fw-bold" id="customer-id-display">
                                            {{ Auth::user()->professor->asaas_customer_id ?? 'Será criado automaticamente' }}
                                        </p>
                                    </div>
                                </div>

                                 <div class="col-md-4">
                                    <div class="info-card">
                                        <label class="form-label text-muted">Chave Pix</label>
                                        <p class="fw-bold" id="customer-id-display">
                                            {{ Auth::user()->professor->asaas_pix_key ?? 'Será criado automaticamente' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="boleto-info">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-card">
                                            <label class="form-label text-muted">Valor</label>
                                            <p class="fw-bold text-success fs-5">R$
                                                {{ number_format(env('VALOR_SISTEMA', 0), 2, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-card">
                                            <label class="form-label text-muted">Data de Vencimento</label>
                                            <p class="fw-bold">{{ date('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-card">
                                            <label class="form-label text-muted">Status</label>
                                            <p class="fw-bold">
                                                <span class="badge bg-warning">Pendente</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Loading do boleto -->
                            <div id="loading-boleto" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Gerando boleto...</span>
                                </div>
                                <p class="mt-2 text-muted">Gerando seu boleto...</p>
                            </div>

                            <!-- Ações do boleto -->
                            <div class="boleto-actions mt-4" style="display: none;">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="#" id="btn-acessar-boleto" class="btn btn-primary btn-lg"
                                        target="_blank">
                                        <i class="fas fa-file-invoice-dollar me-2"></i>
                                        Acessar Boleto
                                    </a>
                                    <a href="{{ route('cliente.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Voltar ao Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de instruções -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-lightbulb me-2 text-warning"></i>
                            Instruções de Pagamento
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        O boleto será gerado automaticamente
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Pagamento via banco ou casa lotérica
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Confirmação automática após pagamento
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Acesso imediato ao sistema
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
        .info-card {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .info-card label {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .info-card p {
            margin-bottom: 0;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .alert {
            border-radius: 12px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.5/socket.io.min.js"></script>
   
    <script>
        $(document).ready(function() {



            // Verifica se já tem customer_id
            @if (Auth::user()->professor->asaas_customer_id)
                gerarBoleto();
            @endif

            

            // Evento para criar customer ID
            $('#btn-criar-customer').click(function() {
                criarCustomerAsaas();
            });

            function criarCustomerAsaas() {
                $('#btn-criar-customer').prop('disabled', true);
                $('#loading-customer').show();

                $.ajax({
                    url: '/api/asaas/criarclienteautomatico',
                    method: 'POST',
                    data: {
                        user_id: $('#user_id').val()

                    },
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#loading-customer').hide();
                        $('#customer-id-display').text(response.customer_id);
                        criarChavePix()
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: 'ID Asaas criado com sucesso. Gerando seu boleto...',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });

                        // Mostra seção do boleto e gera o boleto
                        $('#boleto-section').show();

                        //gerarBoleto();

                    },
                    error: function(xhr) {
                        $('#loading-customer').hide();
                        $('#btn-criar-customer').prop('disabled', false);

                        let errorMessage = 'Erro ao criar ID Asaas';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            }
                        }

                        console.error('Erro detalhado:', xhr.responseText);

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: errorMessage,
                            footer: 'Verifique os dados da sua empresa e endereço.'
                        });
                    }
                });
            }

            function criarChavePix() {
                $('#btn-criar-pix').prop('disabled', true);
                $('#loading-pix').show();

                $.ajax({
                    url: '/api/asaas/criarChavePix',
                    method: 'POST',
                    data: {
                        user_id: '{{Auth::user()->id}}',
                        empresa_id:'{{Auth::user()->empresa->id}}'
                    },
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#loading-pix').hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: response.message,
                            footer: response.pixKey ? `Chave Pix: <b>${response.pixKey}</b>` : ''
                        });

                        // Atualizar campo de exibição se houver
                        if (response.pixKey) {
                            $('#pix-key-display').text(response.pixKey);
                        }

                        $('#btn-criar-pix').prop('disabled', false);
                    },
                    error: function(xhr) {
                        $('#loading-pix').hide();
                        $('#btn-criar-pix').prop('disabled', false);

                        let errorMessage = 'Erro ao criar chave Pix.';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            }
                        }

                        console.error('Erro detalhado:', xhr.responseText);

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: errorMessage
                        });
                    }
                });
            }
            @if (Auth::user()->professor->asaas_pix_key == '')
                criarChavePix();
            @endif

            function gerarBoleto() {
                $('#loading-boleto').show();

                $.ajax({
                    url: '/api/empresa/gerar-boleto',
                    method: 'POST',
                    data: {
                        "customer_id": "{{ Auth::user()->professor->asaas_customer_id }}",
                        "billingType": "BOLETO",
                        "value": {{ env('VALOR_SISTEMA') }},
                        "dueDate": "{{ date('Y-m-d') }}",
                        "description": "Pagamento do sistema"
                    },
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        $('#loading-boleto').hide();
                        $('#boleto-gerado-inicio').val(response.data.id);

                        if (response.status === 'success') {
                            // Atualiza informações do boleto
                            $('.boleto-info').html(`
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-card">
                                            <label class="form-label text-muted">Valor</label>
                                            <p class="fw-bold text-success fs-5">R$ ${parseFloat(response.data.value).toFixed(2).replace('.', ',')}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-card">
                                            <label class="form-label text-muted">Data de Vencimento</label>
                                            <p class="fw-bold">${formatarData(response.data.dueDate)}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-card">
                                            <label class="form-label text-muted">Status</label>
                                            <p class="fw-bold">
                                                <span class="badge bg-warning">Pendente</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `);

                            // Mostra botão de acesso ao boleto
                            $('#btn-acessar-boleto').attr('href', response.data.invoiceUrl);
                            $('.boleto-actions').show();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro ao gerar boleto: ' + response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#loading-boleto').hide();
                        console.error('Erro ao gerar boleto:', xhr.responseText);

                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Erro ao gerar boleto. Tente novamente.'
                        });
                    }
                });
            }

            function formatarData(data) {
                const d = new Date(data);
                return d.toLocaleDateString('pt-BR');
            }
        });

        // Socket.IO para receber confirmação de pagamento
        $(document).ready(function() {
            var socket = io('https://www.comunidadeppg.com.br:3000');

            socket.on('connect', function() {
                console.log('Conectado ao servidor Socket.IO.');
            });

            socket.on('enviarpedidoentregadores', function(recebendopagamento) {
                console.log('Pagamento recebido:', recebendopagamento.payment.id);
                $("#boleto-gerado-fim").val(recebendopagamento.payment.id);
                console.log('Tipo de evento:', recebendopagamento.event);
                if (recebendopagamento.event == "PAYMENT_RECEIVED") {
                    if ($('#boleto-gerado-inicio').val() == recebendopagamento.payment.id) {
                        console.log("PAGAMENTO CONFIRMADO!");

                        // Atualiza status para pago
                        $('.boleto-info .badge').removeClass('bg-warning').addClass('bg-success').text(
                            'Pago');

                            

                        Swal.fire({
                            icon: 'success',
                            title: 'Pagamento Confirmado!',
                            text: 'Seu pagamento foi confirmado! Você será redirecionado para o dashboard.',
                            showConfirmButton: false,
                            timer: 5000
                        }).then(() => {
                            window.location.href = "/cliente/empresa/dashboard";
                        });
                    }
                }
            });

            socket.on('disconnect', function() {
                console.log('Desconectado do servidor Socket.IO.');
            });
        });
    </script>
</x-admin.layout>
