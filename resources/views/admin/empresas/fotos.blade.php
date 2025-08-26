<x-admin.layout title="Pagamento de Boleto">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 10%">
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
                        <p><strong>Valor:</strong> R$ {{ number_format($boleto->valor ?? 100.0, 2, ',', '.') }}</p>
                        <p><strong>Data de Vencimento:</strong>
                            {{ \Carbon\Carbon::parse($boleto->data_vencimento ?? now())->format('d/m/Y') }}</p>
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
            $('.btn-primary').on('click', function() {
                // Verificar status após clicar no boleto
                setTimeout(function() {
                    $.ajax({
                        url: "{{ route('empresa.verificarStatus', ['empresaId' => Auth::user()->empresa->id]) }}",
                        method: "GET",
                        success: function(response) {
                            if (response.status === 'ativo') {
                                alert(
                                    'Pagamento confirmado! O sistema foi desbloqueado.'
                                    );
                                window.location.href =
                                    "{{ route('cliente.dashboard') }}";
                            }
                        },
                        error: function(xhr) {
                            console.error('Erro ao verificar status:', xhr
                                .responseText);
                        }
                    });
                }, 2000); // Aguarda 2 segundos para dar tempo de carregar o boleto
            });
        });
    </script>
</x-admin.layout>
