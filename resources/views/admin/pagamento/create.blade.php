<x-admin.layout title="Cadastrar Pagamento">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <x-header.titulo pageTitle="{{ isset($model) ? 'Editar Gateway de Pagamento' : 'Cadastrar Gateway de Pagamento' }}" />
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ isset($model) ? route('empresa.pagamento.update', $model->id) : route('empresa.pagamento.store') }}">
                                @csrf
                                @if(isset($model))
                                    @method('PUT')
                                @endif

                                <!-- Nome do Gateway -->
                                <div class="form-group mb-3">
                                    <label for="name">Gateway de Pagamento</label>
                                    <select name="name" id="name" class="form-control" required>
                                        <option value="" disabled {{ !isset($model) ? 'selected' : '' }}>Selecione um Gateway</option>
                                        <option value="asaas" {{ isset($model) && $model->name == 'asaas' ? 'selected' : '' }}>Asaas</option>
                                        <option value="stripe" {{ isset($model) && $model->name == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                        <option value="mercadopago" {{ isset($model) && $model->name == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                                    </select>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Chave de API -->
                                <div class="form-group mb-3">
                                    <label for="api_key">Chave de API</label>
                                    <input type="text" name="api_key" id="api_key" class="form-control"
                                           value="{{ old('api_key', isset($model) ? $model->api_key : '') }}"
                                           placeholder="Insira a chave de API do gateway" required>
                                    @error('api_key')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Modo (Teste/Produção) -->
                                <div class="form-group mb-3">
                                    <label>Modo de Operação</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="mode" id="mode_sandbox" value="sandbox"
                                                   class="form-check-input" {{ old('mode', isset($model) && $model->mode == 'sandbox' ? 'checked' : '') }}>
                                            <label class="form-check-label" for="mode_sandbox">Teste (Sandbox)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="mode" id="mode_production" value="production"
                                                   class="form-check-input" {{ old('mode', isset($model) && $model->mode == 'production' ? 'checked' : '') }}>
                                            <label class="form-check-label" for="mode_production">Produção</label>
                                        </div>
                                    </div>
                                    @error('mode')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Métodos de Pagamento -->
                                <div class="form-group mb-3">
                                    <label>Métodos de Pagamento</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="methods[]" id="method_pix" value="pix"
                                                   class="form-check-input" {{ isset($model) && in_array('pix', $model->methods ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="method_pix">PIX</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="methods[]" id="method_boleto" value="boleto"
                                                   class="form-check-input" {{ isset($model) && in_array('boleto', $model->methods ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="method_boleto">Boleto</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" name="methods[]" id="method_card" value="card"
                                                   class="form-check-input" {{ isset($model) && in_array('card', $model->methods ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="method_card">Cartão</label>
                                        </div>
                                    </div>
                                    @error('methods')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Conta do Dono do SaaS (Split) -->
                                <div class="form-group mb-3">
                                    <label for="split_account">Conta do Dono do SaaS (E-mail ou ID)</label>
                                    <input type="text" name="split_account" id="split_account" class="form-control"
                                           value="{{ old('split_account', isset($model) ? $model->split_account : '') }}"
                                           placeholder="Insira o e-mail ou ID da conta do dono do SaaS">
                                    @error('split_account')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Tarifa -->
                                <div class="form-group mb-3">
                                    <label for="tariff_value">Tarifa por Aula</label>
                                    <div class="input-group">
                                        <select name="tariff_type" class="form-control w-25" required>
                                            <option value="fixed" {{ old('tariff_type', isset($model) && $model->tariff_type == 'fixed' ? 'selected' : '') }}>Fixa</option>
                                            <option value="percentage" {{ old('tariff_type', isset($model) && $model->tariff_type == 'percentage' ? 'selected' : '') }}>Percentual</option>
                                        </select>
                                        <input type="number" name="tariff_value" id="tariff_value" class="form-control"
                                               value="{{ old('tariff_value', isset($model) ? $model->tariff_value : '') }}"
                                               placeholder="Valor da tarifa" step="0.01" required>
                                        <span class="input-group-text">{{ old('tariff_type', isset($model) && $model->tariff_type == 'percentage' ? '%' : 'R$') }}</span>
                                    </div>
                                    @error('tariff_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('tariff_value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="form-group mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="1" {{ old('status', isset($model) && $model->status == 1 ? 'selected' : '') }}>Ativo</option>
                                        <option value="0" {{ old('status', isset($model) && $model->status == 0 ? 'selected' : '') }}>Inativo</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Botões de Ação -->
                                <div class="form-group mb-0">
                                    <button type="button" class="btn btn-secondary me-2" onclick="testConnection()">Testar Conexão</button>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                    <a href="{{ route('empresa.pagamento.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para Testar Conexão -->
    <script>
        function testConnection() {
            const apiKey = document.getElementById('api_key').value;
            const gateway = document.getElementById('name').value;
            fetch('{{ route('empresa.pagamento.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ api_key: apiKey, gateway: gateway })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Conexão bem-sucedida com o gateway!');
                } else {
                    alert('Erro ao testar conexão: ' + data.error);
                }
            })
            .catch(error => {
                alert('Erro ao testar conexão: ' + error.message);
            });
        }
    </script>
</x-admin.layout>