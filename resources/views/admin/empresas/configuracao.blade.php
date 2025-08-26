<x-admin.layout title="Configurar Métodos de Pagamento">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <!-- Page Header -->
            <x-header.titulo
                pageTitle="{{ isset($paymentConfig) ? 'Editar Configurações de Pagamento' : 'Configurar Métodos de Pagamento' }}" />
            <!-- /Page Header -->

            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Exibir mensagem de erro -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST"
                                action="{{ isset($paymentConfig) ? route('empresa.tipopagamento.config.update', $paymentConfig->empresa_id) : route('empresa.tipopagamento.config.store') }}">
                                @csrf
                                @if (isset($paymentConfig))
                                    @method('PUT')
                                @endif

                                <!-- Métodos de Pagamento -->
                                <div class="form-group mb-3">
                                    <label>Métodos de Pagamento</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="pix_enabled" id="pix_enabled" value="1"
                                                class="form-check-input"
                                                {{ old('pix_enabled', $paymentConfig->pix_enabled ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pix_enabled">PIX</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="cartao_enabled" id="cartao_enabled"
                                                value="1" class="form-check-input"
                                                {{ old('cartao_enabled', $paymentConfig->cartao_enabled ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cartao_enabled">Cartão de
                                                Crédito</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="presencial_enabled" id="presencial_enabled"
                                                value="1" class="form-check-input"
                                                {{ old('presencial_enabled', $paymentConfig->presencial_enabled ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="presencial_enabled">Presencial</label>
                                        </div>
                                    </div>
                                    @error('pix_enabled')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('cartao_enabled')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('presencial_enabled')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Configurações do PIX -->
                                <div class="form-group mb-3" id="pix_config_section"
                                    style="{{ old('pix_enabled', $paymentConfig->pix_enabled ?? true) ? '' : 'display: none;' }}">
                                    <label>Configurações do PIX</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="pix_expiration_minutes">Tempo de Expiração (minutos)</label>
                                            <input type="number" name="pix_config[expiration_minutes]"
                                                id="pix_expiration_minutes" class="form-control"
                                                value="{{ old('pix_config.expiration_minutes', $paymentConfig->pix_config['expiration_minutes'] ?? 30) }}"
                                                placeholder="Ex.: 30" min="1">
                                            @error('pix_config.expiration_minutes')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" name="pix_config[show_qr_code]"
                                                    id="pix_show_qr_code" value="1" class="form-check-input"
                                                    {{ old('pix_config.show_qr_code', $paymentConfig->pix_config['show_qr_code'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="pix_show_qr_code">Exibir QR
                                                    Code</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" name="pix_config[auto_generate]"
                                                    id="pix_auto_generate" value="1" class="form-check-input"
                                                    {{ old('pix_config.auto_generate', $paymentConfig->pix_config['auto_generate'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="pix_auto_generate">Gerar
                                                    Automaticamente</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Configurações do Cartão -->
                                <div class="form-group mb-3" id="cartao_config_section"
                                    style="{{ old('cartao_enabled', $paymentConfig->cartao_enabled ?? true) ? '' : 'display: none;' }}">
                                    <label>Configurações do Cartão de Crédito</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" name="cartao_config[installments_enabled]"
                                                    id="cartao_installments_enabled" value="1"
                                                    class="form-check-input"
                                                    {{ old('cartao_config.installments_enabled', $paymentConfig->cartao_config['installments_enabled'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="cartao_installments_enabled">Habilitar Parcelamento</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cartao_max_installments">Máximo de Parcelas</label>
                                            <input type="number" name="cartao_config[max_installments]"
                                                id="cartao_max_installments" class="form-control"
                                                value="{{ old('cartao_config.max_installments', $paymentConfig->cartao_config['max_installments'] ?? 12) }}"
                                                placeholder="Ex.: 12" min="1">
                                            @error('cartao_config.max_installments')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cartao_min_installment_amount">Valor Mínimo por Parcela</label>
                                            <input type="number" name="cartao_config[min_installment_amount]"
                                                id="cartao_min_installment_amount" class="form-control"
                                                value="{{ old('cartao_config.min_installment_amount', $paymentConfig->cartao_config['min_installment_amount'] ?? 30.0) }}"
                                                placeholder="Ex.: 30.00" step="0.01" min="0">
                                            @error('cartao_config.min_installment_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Configurações do Presencial -->
                                <div class="form-group mb-3" id="presencial_config_section"
                                    style="{{ old('presencial_enabled', $paymentConfig->presencial_enabled ?? true) ? '' : 'display: none;' }}">
                                    <label>Configurações do Pagamento Presencial</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="presencial_cancellation_hours">Horas para Cancelamento</label>
                                            <input type="number" name="presencial_config[cancellation_hours]"
                                                id="presencial_cancellation_hours" class="form-control"
                                                value="{{ old('presencial_config.cancellation_hours', $paymentConfig->presencial_config['cancellation_hours'] ?? 24) }}"
                                                placeholder="Ex.: 24" min="0">
                                            @error('presencial_config.cancellation_hours')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label>Métodos Aceitos</label>
                                            <div class="form-check">
                                                <input type="checkbox" name="presencial_config[accepted_methods][]"
                                                    id="presencial_dinheiro" value="dinheiro"
                                                    class="form-check-input"
                                                    {{ old('presencial_config.accepted_methods', in_array('dinheiro', $paymentConfig->presencial_config['accepted_methods'] ?? ['dinheiro', 'pix', 'cartao'])) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="presencial_dinheiro">Dinheiro</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="presencial_config[accepted_methods][]"
                                                    id="presencial_pix" value="pix" class="form-check-input"
                                                    {{ old('presencial_config.accepted_methods', in_array('pix', $paymentConfig->presencial_config['accepted_methods'] ?? ['dinheiro', 'pix', 'cartao'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="presencial_pix">PIX</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="presencial_config[accepted_methods][]"
                                                    id="presencial_cartao" value="cartao" class="form-check-input"
                                                    {{ old('presencial_config.accepted_methods', in_array('cartao', $paymentConfig->presencial_config['accepted_methods'] ?? ['dinheiro', 'pix', 'cartao'])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="presencial_cartao">Cartão</label>
                                            </div>
                                            @error('presencial_config.accepted_methods')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" name="presencial_config[require_confirmation]"
                                                    id="presencial_require_confirmation" value="1"
                                                    class="form-check-input"
                                                    {{ old('presencial_config.require_confirmation', $paymentConfig->presencial_config['require_confirmation'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="presencial_require_confirmation">Exige Confirmação</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="form-group mb-0">
                                    <button type="button" class="btn btn-secondary me-2"
                                        onclick="testConnection()">Testar Conexão</button>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                    <a href="{{ route('empresa.pagamento.index', $paymentConfig->empresa_id ?? '') }}"
                                        class="btn btn-outline-secondary">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para Testar Conexão e Mostrar/Esconder Configurações -->
    <script>
        function testConnection() {
            const pixEnabled = document.getElementById('pix_enabled').checked;
            const cartaoEnabled = document.getElementById('cartao_enabled').checked;
            const pixConfig = {
                expiration_minutes: document.getElementById('pix_expiration_minutes')?.value,
                show_qr_code: document.getElementById('pix_show_qr_code')?.checked,
                auto_generate: document.getElementById('pix_auto_generate')?.checked
            };
            const cartaoConfig = {
                installments_enabled: document.getElementById('cartao_installments_enabled')?.checked,
                max_installments: document.getElementById('cartao_max_installments')?.value,
                min_installment_amount: document.getElementById('cartao_min_installment_amount')?.value
            };

            fetch('{{ route('empresa.pagamento.test') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        pix_enabled: pixEnabled,
                        cartao_enabled: cartaoEnabled,
                        pix_config: pixConfig,
                        cartao_config: cartaoConfig
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Conexão bem-sucedida com os métodos de pagamento!');
                    } else {
                        alert('Erro ao testar conexão: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Erro ao testar conexão: ' + error.message);
                });
        }

        // Mostrar/esconder seções de configuração com base nos checkboxes
        document.getElementById('pix_enabled').addEventListener('change', function() {
            document.getElementById('pix_config_section').style.display = this.checked ? '' : 'none';
        });
        document.getElementById('cartao_enabled').addEventListener('change', function() {
            document.getElementById('cartao_config_section').style.display = this.checked ? '' : 'none';
        });
        document.getElementById('presencial_enabled').addEventListener('change', function() {
            document.getElementById('presencial_config_section').style.display = this.checked ? '' : 'none';
        });
    </script>
</x-admin.layout>
