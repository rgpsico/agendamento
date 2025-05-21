<x-admin.layout title="Integração de Pagamentos">
    <div class="page-wrapper">
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <x-header.titulo pageTitle="Integração de Pagamentos" />
            <!-- /Page Header -->
            
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
                                    <small class="form-text text-muted">Atualmente, apenas o Asaas está disponível para integração.</small>
                                </div>

                                <!-- Resultado da Integração -->
                                <div id="resultado" class="mt-3" style="display: none;">
                                    <div class="form-group mb-3">
                                        <label>Status da Integração</label>
                                        <div class="alert alert-info" role="alert" id="status"></div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="customerId">Customer ID</label>
                                        <input type="text" id="customerId" class="form-control" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="walletId">Wallet ID</label>
                                        <div class="input-group">
                                            <input type="text" id="walletId" class="form-control" readonly>
                                            <button type="button" id="copiarWallet" class="btn btn-outline-secondary" style="display: none;">Copiar</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="form-group mb-0">
                                    <button type="button" id="integrarBtn" class="btn btn-primary">Integrar</button>
                                    <a href="" class="btn btn-outline-secondary">Voltar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- /Page Wrapper -->

    <!-- Script para Integração -->
    <script>
        document.getElementById('integrarBtn').addEventListener('click', function() {
            const gatewayName = document.getElementById('gateway_name').value;
            const professorId = {{ Auth::check() ? Auth::user()->professor->id ?? 'null' : 'null' }};

            if (!gatewayName) {
                alert('Por favor, selecione um gateway de pagamento.');
                return;
            }

            if (professorId === null) {
                document.getElementById('status').textContent = 'Erro: Você precisa estar logado como professor.';
                document.getElementById('resultado').style.display = 'block';
                return;
            }

            fetch('/integrar/asaas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ professor_id: professorId })
            })
            .then(response => response.json())
            .then(data => {
                const resultado = document.getElementById('resultado');
                const status = document.getElementById('status');
                const customerId = document.getElementById('customerId');
                const walletId = document.getElementById('walletId');
                const copiarWallet = document.getElementById('copiarWallet');

                if (data.success) {
                    status.textContent = data.message;
                    customerId.value = data.customerId;
                    walletId.value = data.walletId || 'Não disponível';
                    copiarWallet.style.display = data.walletId ? 'inline-block' : 'none';
                    resultado.style.display = 'block';
                } else {
                    status.textContent = 'Erro: ' + data.message;
                    resultado.style.display = 'block';
                }
            })
            .catch(error => {
                document.getElementById('status').textContent = 'Erro na requisição: ' + error.message;
                document.getElementById('resultado').style.display = 'block';
            });
        });

        // Funcionalidade para copiar Wallet ID
        document.getElementById('copiarWallet').addEventListener('click', function() {
            const walletId = document.getElementById('walletId').value;
            navigator.clipboard.writeText(walletId)
                .then(() => alert('Wallet ID copiado para a área de transferência!'))
                .catch(err => console.error('Erro ao copiar: ', err));
        });
    </script>
</x-admin.layout>