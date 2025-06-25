<x-admin.layout title="Integração de Pagamentos">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Integração de Pagamentos" />
            <!-- /Page Header -->
            <!-- Modal de Explicação -->
            @include('admin.integracoes._partials.explicacaoModal')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="integracaoForm">
                                @csrf

                                <!-- Seleção do Gateway -->
                                <div class="form-group mb-3">
                                    <label for="gateway_name">Gateway de Pagamento</label>
                                    <select name="gateway_name" id="gateway_name" class="form-control" required>
                                        <option value="" disabled selected>Selecione um Gateway</option>
                                        <option value="asaas">Asaas</option>
                                        <!-- Futuras opções -->
                                        <option value="stripe" disabled>Stripe (Em breve)</option>
                                        <option value="paypal" disabled>PayPal (Em breve)</option>
                                    </select>
                                    <small class="form-text text-muted">Atualmente, apenas o Asaas está disponível para
                                        integração.</small>
                                </div>

                                <!-- Verificar se já possui wallet -->
                                @if (Auth::user()->professor && Auth::user()->professor->asaas_wallet_id)
                                    <div class="alert alert-success" role="alert">

                                        <h5 class="alert-heading">Já Integrado!</h5>

                                        <p>Você já possui uma subconta integrada no Asaas.</p>
                                        <strong>Cliente_id Asaas:</strong>
                                        {{ Auth::user()->professor->asaas_customer_id }}
                                        <hr>
                                        <p class="mb-0">
                                            <strong>Wallet ID:</strong> {{ Auth::user()->professor->asaas_wallet_id }}

                                            <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                                                onclick="copiarTexto('{{ Auth::user()->professor->asaas_wallet_id }}')">Copiar</button>
                                        </p>
                                    </div>
                                @else
                                    <div id="dadosAsaas" style="display: none;">
                                        <h5 class="mb-3">Dados Obrigatórios para Integração</h5>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="name">Nome Completo *</label>
                                                    <input type="text" name="name" id="name"
                                                        class="form-control" required
                                                        value="{{ Auth::user()->nome ?? '' }}">
                                                    <small class="form-text text-muted">Nome completo como no
                                                        documento</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="email">Email *</label>
                                                    <input type="email" name="email" id="email"
                                                        class="form-control" required
                                                        value="{{ Auth::user()->email ?? '' }}">
                                                    <small class="form-text text-muted">Email válido para receber
                                                        notificações</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="cpfCnpj">CPF *</label>
                                                    <input type="text" name="cpfCnpj" id="cpfCnpj"
                                                        class="form-control" required maxlength="11">
                                                    <small class="form-text text-muted">Apenas números (11
                                                        dígitos)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="phone">Telefone *</label>
                                                    <input type="text" name="phone" id="phone"
                                                        class="form-control" required
                                                        value="{{ Auth::user()->telefone ?? '' }}">
                                                    <small class="form-text text-muted">Ex: 11987654321</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="mobilePhone">Celular *</label>
                                                    <input type="text" name="mobilePhone" id="mobilePhone"
                                                        class="form-control" required
                                                        value="{{ Auth::user()->telefone ?? '' }}">
                                                    <small class="form-text text-muted">Ex: 11987654321</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="birthDate">Data de Nascimento *</label>
                                                    <input type="date" name="birthDate" id="birthDate"
                                                        class="form-control" required
                                                        value="{{ Auth::user()->data_nascimento ? Auth::user()->data_nascimento->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="incomeValue">Renda Mensal *</label>
                                                    <input type="number" name="incomeValue" id="incomeValue"
                                                        class="form-control" required step="0.01" min="0">
                                                    <small class="form-text text-muted">Valor em reais (R$)</small>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="mb-3">Endereço</h6>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group mb-3">
                                                    <label for="address">Logradouro *</label>
                                                    <input type="text" name="address" id="address"
                                                        class="form-control" required>
                                                    <small class="form-text text-muted">Rua, Avenida, etc.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="addressNumber">Número *</label>
                                                    <input type="text" name="addressNumber" id="addressNumber"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="complement">Complemento</label>
                                                    <input type="text" name="complement" id="complement"
                                                        class="form-control">
                                                    <small class="form-text text-muted">Apartamento, sala, etc.
                                                        (opcional)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="province">Bairro *</label>
                                                    <input type="text" name="province" id="province"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <label for="postalCode">CEP *</label>
                                                    <input type="text" name="postalCode" id="postalCode"
                                                        class="form-control" required maxlength="8">
                                                    <small class="form-text text-muted">Apenas números (8
                                                        dígitos)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Resultado da Integração -->
                                    <div id="resultado" class="mt-3" style="display: none;">
                                        <div class="form-group mb-3">
                                            <label>Status da Integração</label>
                                            <div class="alert alert-info" role="alert" id="status"></div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="walletId">Wallet ID</label>
                                            <div class="input-group">
                                                <input type="text" id="walletId" class="form-control" readonly>
                                                <button type="button" id="copiarWallet"
                                                    class="btn btn-outline-secondary"
                                                    style="display: none;">Copiar</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Botões de Ação -->
                                @if (!Auth::user()->professor || !Auth::user()->professor->asaas_wallet_id)
                                    <div class="form-group mb-0">
                                        <button type="button" id="integrarBtn" class="btn btn-primary">
                                            <span id="loadingSpinner" class="spinner-border spinner-border-sm"
                                                role="status" style="display: none;"></span>
                                            <span id="btnText">Integrar</span>
                                        </button>
                                        <a href="" class="btn btn-outline-secondary">Voltar</a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Script para Integração -->
    <script>
        // Função para copiar texto
        const dadosAsaas = document.getElementById('dadosAsaas');

        function copiarTexto(texto) {
            navigator.clipboard.writeText(texto)
                .then(() => alert('Copiado para a área de transferência!'))
                .catch(err => console.error('Erro ao copiar: ', err));
        }


        document.getElementById('entendi').addEventListener('click', function() {
            dadosAsaas.style.display = 'block';
        });
        // Mostrar/ocultar campos baseado no gateway selecionado
        document.getElementById('gateway_name').addEventListener('change', function() {
            if (this.value === 'asaas') {
                dadosAsaas.style.display = 'block';
            } else {
                dadosAsaas.style.display = 'none';
            }
        });

        // Máscara para CPF
        document.getElementById('cpfCnpj').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 11);
        });

        // Máscara para CEP
        document.getElementById('postalCode').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 8);
        });

        // Máscara para telefones
        ['phone', 'mobilePhone'].forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        });

        // Buscar CEP automaticamente
        document.getElementById('postalCode').addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('address').value = data.logradouro || '';
                            document.getElementById('province').value = data.bairro || '';
                        }
                    })
                    .catch(error => console.log('Erro ao buscar CEP:', error));
            }
        });

        document.getElementById('integrarBtn').addEventListener('click', function() {
            const gatewayName = document.getElementById('gateway_name').value;

            if (!gatewayName) {
                alert('Por favor, selecione um gateway de pagamento.');
                return;
            }

            if (gatewayName === 'asaas') {
                // Validar campos obrigatórios
                const requiredFields = ['name', 'email', 'cpfCnpj', 'phone', 'mobilePhone', 'birthDate',
                    'incomeValue', 'address', 'addressNumber', 'province', 'postalCode'
                ];
                const missingFields = [];

                requiredFields.forEach(field => {
                    const element = document.getElementById(field);
                    if (!element.value.trim()) {
                        missingFields.push(element.previousElementSibling.textContent.replace(' *', ''));
                    }
                });

                if (missingFields.length > 0) {
                    alert('Por favor, preencha os campos obrigatórios: ' + missingFields.join(', '));
                    return;
                }

                // Validar CPF (11 dígitos)
                const cpf = document.getElementById('cpfCnpj').value;
                if (cpf.length !== 11) {
                    alert('CPF deve ter exatamente 11 dígitos.');
                    return;
                }

                // Validar CEP (8 dígitos)
                const cep = document.getElementById('postalCode').value;
                if (cep.length !== 8) {
                    alert('CEP deve ter exatamente 8 dígitos.');
                    return;
                }

                // Mostrar loading
                document.getElementById('loadingSpinner').style.display = 'inline-block';
                document.getElementById('btnText').textContent = 'Integrando...';
                this.disabled = true;

                // Coletar dados do formulário
                const formData = {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    cpfCnpj: document.getElementById('cpfCnpj').value,
                    phone: document.getElementById('phone').value,
                    mobilePhone: document.getElementById('mobilePhone').value,
                    birthDate: document.getElementById('birthDate').value,
                    incomeValue: parseFloat(document.getElementById('incomeValue').value),
                    address: document.getElementById('address').value,
                    addressNumber: document.getElementById('addressNumber').value,
                    complement: document.getElementById('complement').value,
                    province: document.getElementById('province').value,
                    postalCode: document.getElementById('postalCode').value
                };

                fetch('/subcontas', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin', // Importante para Sanctum SPA
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        const resultado = document.getElementById('resultado');
                        const status = document.getElementById('status');
                        const walletId = document.getElementById('walletId');
                        const copiarWallet = document.getElementById('copiarWallet');

                        if (data.success) {
                            status.className = 'alert alert-success';
                            status.textContent = data.message;
                            walletId.value = data.data.professor.asaas_wallet_id || 'Não disponível';
                            copiarWallet.style.display = data.data.professor.asaas_wallet_id ? 'inline-block' :
                                'none';
                            resultado.style.display = 'block';
                        } else {
                            status.className = 'alert alert-danger';
                            status.textContent = 'Erro: ' + data.message;
                            resultado.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        document.getElementById('status').className = 'alert alert-danger';
                        document.getElementById('status').textContent = 'Erro na requisição: ' + error.message;
                        document.getElementById('resultado').style.display = 'block';
                    })
                    .finally(() => {
                        // Esconder loading
                        document.getElementById('loadingSpinner').style.display = 'none';
                        document.getElementById('btnText').textContent = 'Integrar';
                        this.disabled = false;

                        Swal.fire({
                            icon: 'success',
                            title: 'Integração confirada!',
                            text: 'Você será redirecionado ao pagamento do boleto.',
                            showConfirmButton: false,
                            timer: 5000
                        }).then(() => {
                            window.location.href = "/empresa/pagamento/boleto";
                        });

                    });
            }
        });

        // Funcionalidade para copiar Wallet ID
        document.getElementById('copiarWallet').addEventListener('click', function() {
            const walletId = document.getElementById('walletId').value;
            navigator.clipboard.writeText(walletId)
                .then(() => alert('Wallet ID copiado para a área de transferência!'))
                .catch(err => console.error('Erro ao copiar: ', err));
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Verifica se o professor já está integrado
            const integrado =
                {{ Auth::user()->professor && Auth::user()->professor->asaas_wallet_id ? 'true' : 'false' }};

            if (!integrado) {
                setTimeout(function() {
                    const modal = new bootstrap.Modal(document.getElementById('explicacaoModal'));
                    modal.show();
                }, 2000); // 2000 milissegundos = 2 segundos
            }
        });
    </script>
</x-admin.layout>
