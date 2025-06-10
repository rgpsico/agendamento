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
                <div class="card-body">
                    <x-alert />
                    <div class="boleto-info">
                        <p><strong>Empresa:</strong> {{ Auth::user()->empresa->nome ?? 'Não informado' }}</p>
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

    <!-- Script para re-verificar status após tentativa de pagamento -->
    <script>
        $(document).ready(function() {
            gerarBoleto();

            function gerarBoleto() {
                $.ajax({
                    url: '/api/empresa/gerar-boleto',
                    method: 'POST',
                    data: {
                        "customer_id": "cus_000006746814",
                        "billingType": "BOLETO",
                        "value": 100.00,
                        "dueDate": "2025-06-11",
                        "description": "Pagamento de teste"
                    },
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        console.log(response.data)
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
    </script>

</x-admin.layout>
