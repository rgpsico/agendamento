
<x-admin.layout title="Gerenciar Chave PIX">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <x-header.titulo pageTitle="Gerenciar Chave PIX" />
            <!-- /Page Header -->
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            @if(Auth::user()->professor && Auth::user()->professor->asaas_wallet_id)
                                <!-- Exibir chave PIX existente -->
                                @if(Auth::user()->professor->asaas_pix_key)
                                    <div class="alert alert-info" role="alert">
                                        <h5 class="alert-heading">Chave PIX Registrada</h5>
                                        <p><strong>Chave PIX:</strong> {{ json_decode(Auth::user()->professor->asaas_pix_key)->key }}</p>
                                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="copiarTexto('{{ json_decode(Auth::user()->professor->asaas_pix_key)->key }}')">Copiar</button>
                                    </div>
                                @else
                                    <!-- Formulário para criar chave PIX -->
                                    <form id="pixForm">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="pix_key_type">Tipo de Chave PIX</label>
                                            <select name="pix_key_type" id="pix_key_type" class="form-control" required>
                                                <option value="" disabled selected>Selecione o tipo de chave</option>
                                                <option value="cpf">CPF</option>
                                                <option value="email">E-mail</option>
                                                <option value="phone">Telefone</option>
                                                <option value="random">Aleatória</option>
                                            </select>
                                            <small class="form-text text-muted">Escolha o tipo de chave PIX que deseja criar.</small>
                                        </div>

                                        <div class="form-group mb-3" id="pix_key_value_container" style="display: none;">
                                            <label for="pix_key_value">Valor da Chave PIX</label>
                                            <input type="text" name="pix_key_value" id="pix_key_value" class="form-control" placeholder="Digite o valor da chave">
                                            <small class="form-text text-muted" id="pix_key_value_hint"></small>
                                        </div>

                                        <div class="form-group mb-3">
                                            <button type="button" id="criarPixBtn" class="btn btn-primary">
                                                <span id="pixLoadingSpinner" class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                                <span id="pixBtnText">Criar Chave PIX</span>
                                            </button>
                                            <a href="{{ route('integracao.assas.escola') }}" class="btn btn-outline-secondary">Voltar</a>
                                        </div>
                                    </form>

                                    <!-- Resultado da Criação -->
                                    <div id="resultado" class="mt-3" style="display: none;">
                                        <div class="form-group mb-3">
                                            <label>Status da Criação</label>
                                            <div class="alert alert-info" role="alert" id="status"></div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="pixKey">Chave PIX</label>
                                            <div class="input-group">
                                                <input type="text" id="pixKey" class="form-control" readonly>
                                                <button type="button" id="copiarPixKey" class="btn btn-outline-secondary" style="display: none;">Copiar</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning" role="alert">
                                    <h5 class="alert-heading">Integração Necessária</h5>
                                    <p>Você precisa integrar uma subconta no Asaas antes de criar uma chave PIX.</p>
                                    <a href="{{ route('integracao.assas.escola') }}" class="btn btn-primary">Ir para Integração</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

    <!-- Script para Criação da Chave PIX -->
    <script>
        // Função para copiar texto
        function copiarTexto(texto) {
            navigator.clipboard.writeText(texto)
                .then(() => alert('Chave PIX copiada para a área de transferência!'))
                .catch(err => console.error('Erro ao copiar: ', err));
        }

        // Mostrar/ocultar campo de valor da chave PIX
        const pixKeyType = document.getElementById('pix_key_type');
        if (pixKeyType) {
            pixKeyType.addEventListener('change', function() {
                const pixKeyValueContainer = document.getElementById('pix_key_value_container');
                const pixKeyValue = document.getElementById('pix_key_value');
                const pixKeyValueHint = document.getElementById('pix_key_value_hint');

                if (this.value === 'random') {
                    pixKeyValueContainer.style.display = 'none';
                    pixKeyValue.value = '';
                } else {
                    pixKeyValueContainer.style.display = 'block';
                    if (this.value === 'cpf') {
                        pixKeyValueHint.textContent = 'Digite apenas os números do CPF (11 dígitos)';
                        pixKeyValue.value = '{{ Auth::user()->professor->cpf ?? '' }}';
                    } else if (this.value === 'email') {
                        pixKeyValueHint.textContent = 'Digite o e-mail para a chave PIX';
                        pixKeyValue.value = '{{ Auth::user()->email ?? '' }}';
                    } else if (this.value === 'phone') {
                        pixKeyValueHint.textContent = 'Digite o telefone com DDD (ex: 11987654321)';
                        pixKeyValue.value = '{{ Auth::user()->telefone ?? '' }}';
                    }
                }
            });
        }

        // Criar chave PIX
        const criarPixBtn = document.getElementById('criarPixBtn');
        if (criarPixBtn) {
            criarPixBtn.addEventListener('click', function() {
                const pixKeyType = document.getElementById('pix_key_type').value;
                const pixKeyValue = document.getElementById('pix_key_value').value;

                if (!pixKeyType) {
                    alert('Por favor, selecione o tipo de chave PIX.');
                    return;
                }

                if (pixKeyType !== 'random' && !pixKeyValue) {
                    alert('Por favor, informe o valor da chave PIX.');
                    return;
                }

                // Validar formato do valor da chave PIX
                if (pixKeyType === 'cpf' && pixKeyValue.length !== 11) {
                    alert('CPF deve ter exatamente 11 dígitos.');
                    return;
                }
                if (pixKeyType === 'email' && !pixKeyValue.includes('@')) {
                    alert('E-mail inválido.');
                    return;
                }
                if (pixKeyType === 'phone' && pixKeyValue.length < 10) {
                    alert('Telefone deve ter pelo menos 10 dígitos (incluindo DDD).');
                    return;
                }

                // Mostrar loading
                const pixLoadingSpinner = document.getElementById('pixLoadingSpinner');
                const pixBtnText = document.getElementById('pixBtnText');
                pixLoadingSpinner.style.display = 'inline-block';
                pixBtnText.textContent = 'Criando...';
                criarPixBtn.disabled = true;

                // Mapear tipo de chave para o formato esperado pela API
                const keyTypeMap = {
                    'cpf': 'CPF',
                    'email': 'EMAIL',
                    'phone': 'PHONE',
                    'random': 'EVP'
                };

                // Enviar requisição para criar chave PIX
                fetch('/api/pix/create-key', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        type: 'EVP',
                        key: pixKeyType === 'random' ? null : pixKeyValue,
                        usuario_id: '{{ Auth::user()->id }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const resultado = document.getElementById('resultado');
                    const status = document.getElementById('status');
                    const pixKey = document.getElementById('pixKey');
                    const copiarPixKey = document.getElementById('copiarPixKey');

                    if (data.success) {
                        status.className = 'alert alert-success';
                        status.textContent = 'Chave PIX criada com sucesso! Você agora está habilitado para usar PIX no sistema.';
                        pixKey.value = data.pix_key?.key || 'Não disponível';
                        copiarPixKey.style.display = data.pix_key?.key ? 'inline-block' : 'none';
                        resultado.style.display = 'block';
                        // Atualizar a página para exibir a chave PIX
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        status.className = 'alert alert-danger';
                        status.textContent = 'Erro: ' + (data.message || 'Falha ao criar a chave PIX');
                        resultado.style.display = 'block';
                    }
                })
                .catch(error => {
                    document.getElementById('status').className = 'alert alert-danger';
                    document.getElementById('status').textContent = 'Erro na requisição: ' + error.message;
                    document.getElementById('resultado').style.display = 'block';
                })
                .finally(() => {
                    pixLoadingSpinner.style.display = 'none';
                    pixBtnText.textContent = 'Criar Chave PIX';
                    criarPixBtn.disabled = false;
                });
            });
        }

        // Funcionalidade para copiar chave PIX
        const copiarPixKey = document.getElementById('copiarPixKey');
        if (copiarPixKey) {
            copiarPixKey.addEventListener('click', function() {
                const pixKey = document.getElementById('pixKey').value;
                navigator.clipboard.writeText(pixKey)
                    .then(() => alert('Chave PIX copiada para a área de transferência!'))
                    .catch(err => console.error('Erro ao copiar: ', err));
            });
        }
    </script>
</x-admin.layout>
