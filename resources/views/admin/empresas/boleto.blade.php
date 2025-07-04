<x-admin.layout title="Pagamento de Boleto">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <x-header.titulo pageTitle="Pagamento de Boleto" />
            <!-- /Page Header -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detalhes do Boleto</h4>
                </div>
                <input type="hidden" id="boleto-gerado-inicio" name="" value="">
                <input type="hidden" id="boleto-gerado-fim" name="" value="">
                <div class="card-body">
                    <x-alert />
                    <p><strong>Cliente Asaas:</strong> {{ Auth::user()->professor->asaas_customer_id }}</p>
                    <div class="boleto-info">

                        <p><strong>Empresa:</strong> {{ Auth::user()->empresa->nome }}</p>
                        <p><strong>Valor:</strong> R$ </p>
                        <p><strong>Data de Vencimento:</strong>
                        </p>
                        <p><strong>Status:</strong> Pendente</p>
                    </div>
                    <div class="boleto-actions mt-4">
                        @if (isset($boleto->link))
                            <a href="{{ $boleto->link }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-file-invoice-dollar"></i> Acessar Boleto
                            </a>
                        @else
                            <p class="text-danger">Link do boleto não disponível. Entre em contato com o suporte.</p>
                        @endif
                        <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary ms-2">Voltar ao
                            Dashboard</a>
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
            gerarBoleto();

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
