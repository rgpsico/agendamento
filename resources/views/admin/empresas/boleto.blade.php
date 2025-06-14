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
                <input type="text" id="boleto-gerado-inicio" name="" value="">
                <input type="text" id="boleto-gerado-fim" name="" value="">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.5/socket.io.min.js"></script>
    <!-- Script para re-verificar status após tentativa de pagamento -->
    <script>
        $(document).ready(function() {
            gerarBoleto();

            function gerarBoleto() {
                $.ajax({
                    url: '/api/empresa/gerar-boleto',
                    method: 'POST',
                    data: {
                        "customer_id": "{{ Auth::user()->professor->asaas_customer_id }}",
                        "billingType": "BOLETO",
                        "value": 100.00,
                        "dueDate": "{{ date('Y-m-d') }}",
                        "description": "Pagamento de teste"
                    },
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        console.log(response.data.id)
                        $('#boleto-gerado-inicio').val(response.data.id)

                        if (response.status === 'success') {
                            $('.boleto-info').html(`
                    <p><strong>Empresa:</strong> {{ Auth::user()->empresa->nome }}</p>
                    <p><strong>Valor:</strong> R$ ${parseFloat(response.data.value).toFixed(2).replace('.', ',')}</p>
                    <p><strong>Data de Vencimento:</strong> ${formatarData(response.data.dueDate)}</p>
                    <p><strong>Status:</strong> Pendente</p>
                `);

                            $('.boleto-actions').html(`
                    <a href="${response.data.invoiceUrl}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file-invoice-dollar"></i> Acessar Boleto
                    </a>
                    <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary ms-2">Voltar ao Dashboard</a>
                `);
                        } else {
                            alert('Erro ao gerar boleto: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Erro ao gerar boleto:', xhr.responseText);
                        alert('Erro ao gerar boleto.');
                    }
                });
            }


            function formatarData(data) {
                const d = new Date(data);
                return d.toLocaleDateString('pt-BR');
            }
        });



        $(document).ready(function() {
            // Initialize Socket.IO connection
            var socket = io('https://www.comunidadeppg.com.br:3000');

            // Handle connection
            socket.on('connect', function() {
                console.log('Conectado ao servidor Socket.IO.');
            });

            // Handle 'enviarpedidoentregadores' event
            socket.on('enviarpedidoentregadores', function(recebendopagamento) {
                console.log('pedido para entregador:', recebendopagamento.payment.id);

                $("#boleto-gerado-fim").val(recebendopagamento.payment.id)
                // Verificar se existe pixData e se os IDs coincidem

                if (recebendopagamento.event == "PAYMENT_RECEIVED") {
                    if ($('#boleto-gerado-inicio').val() == recebendopagamento.payment.id) {
                        console.log("PAGAMENTO ESTA OK PODE IR PRO DASHBOAD");

                        Swal.fire({
                            icon: 'success',
                            title: 'Pagamento confirmado!',
                            text: 'Você será redirecionado para o dashboard e já pode começar a usar o sistema.',
                            showConfirmButton: false,
                            timer: 5000
                        }).then(() => {
                            window.location.href = "/cliente/empresa/dashboard";
                        });
                    }

                }
            });

            // Handle disconnection
            socket.on('disconnect', function() {
                console.log('Desconectado do servidor Socket.IO.');
            });
        });
    </script>

</x-admin.layout>
