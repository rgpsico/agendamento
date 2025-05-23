<x-public.layout title="HOME">
    <style>
        #spinner {
            position: fixed; 
            top: 50%; 
            left: 50%; 
            z-index: 9999; 
        }
        
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }
        
        .payment-method.selected {
            border-color: #007bff;
            background-color: #e7f3ff;
        }
        
        .payment-method input[type="radio"] {
            margin-right: 10px;
        }
        
        .payment-details {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .payment-details.active {
            display: block;
        }
        
        .card-form .form-row {
            display: flex;
            gap: 15px;
        }
        
        .card-form .form-group {
            flex: 1;
        }
        
        .pix-info {
            text-align: center;
            padding: 20px;
        }
        
        .pix-qr {
            width: 200px;
            height: 200px;
            background-color: #f0f0f0;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
        }
        
        .payment-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .total-amount {
            font-size: 1.5em;
            font-weight: bold;
            color: #28a745;
        }
    </style>
    
    <!-- Breadcrumb -->
    <x-home.breadcrumb title="PAGAMENTO"/>
    <!-- /Breadcrumb -->
    
    <div id="spinner" class="spinner-border text-primary" role="status" style="display:none;">
        <span class="sr-only">Loading...</span>
    </div>
    
    <div class="content" style="transform: none; min-height: 172.906px;">
        <div class="container" style="transform: none;">
            <div class="row" style="transform: none;">
                <div class="col-md-7 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Exibição de Erros -->
                            @if ($errors->any() || session('error'))
                                <div class="alert alert-danger">
                                    @if (session('error'))
                                        <p>{{ session('error') }}</p>
                                    @endif
                                    @if ($errors->any())
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Resumo do Agendamento -->
                            <div class="payment-summary">
                                <h4 class="card-title mb-3">Resumo do Agendamento</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Data:</strong> <span id="display_data_aula"></span></p>
                                        <p><strong>Hora:</strong> <span id="display_hora_aula"></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Serviço:</strong> <span id="display_titulo"></span></p>
                                        <p class="total-amount">Total: R$ <span id="display_valor_aula"></span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Formulário de Pagamento -->
                            <form action="{{ route('empresa.pagamento.asaas') }}" method="post" id="payment-form">
                                @csrf
                                
                                @php 
                                    $usuario = Auth::user();
                                    $usuario->load('aluno');
                                @endphp

                                <!-- Campos Hidden -->
                                <input type="hidden" name="aluno_id" value="{{ Auth::user()->aluno->id ?? Auth::user()->professor->id }}">
                                <input type="hidden" name="professor_id" value="{{ $professor->id }}">
                                <input type="hidden" name="modalidade_id" value="{{ $professor->modalidade_id ?? 1 }}">
                                <input type="hidden" id="data_aula" name="data_aula" value="">
                                <input type="hidden" id="hora_aula" name="hora_aula" value="">
                                <input type="hidden" id="valor_aula" name="valor_aula" value="">
                                <input type="hidden" id="titulo" name="titulo" value="">
                                <input type="hidden" id="payment_method" name="payment_method" value="">
                                <input type="hidden" id="status" name="status" value="PENDING">

                                <h4 class="card-title mb-4">Forma de Pagamento</h4>

                                <!-- Opção PIX -->
                                <div class="payment-method" data-method="pix">
                                    <label class="d-flex align-items-center">
                                        <input type="radio" name="forma_pagamento" value="pix">
                                        <div>
                                            <strong>PIX</strong>
                                            <p class="mb-0 text-muted">Pagamento instantâneo via PIX</p>
                                        </div>
                                    </label>
                                    <div class="payment-details" id="pix-details">
                                        <div class="pix-info">
                                            <h5>Pagamento via PIX</h5>
                                            <p>Após confirmar, você receberá o código PIX para pagamento</p>
                                            <div class="pix-qr">
                                                <span class="text-muted">QR Code será gerado aqui</span>
                                            </div>
                                            <p class="text-muted">Ou use o código PIX: <strong id="pix-code">Será gerado após confirmação</strong></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Opção Cartão de Crédito -->
                                <div class="payment-method" data-method="card">
                                    <label class="d-flex align-items-center">
                                        <input type="radio" name="forma_pagamento" value="cartao">
                                        <div>
                                            <strong>Cartão de Crédito</strong>
                                            <p class="mb-0 text-muted">Pagamento seguro com cartão</p>
                                        </div>
                                    </label>
                                    <div class="payment-details card-form" id="card-details">
                                        <div class="form-group">
                                            <label>Nome no Cartão</label>
                                            <input type="text" class="form-control" name="card_name" placeholder="Nome como está no cartão" value="{{ old('card_name') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Número do Cartão</label>
                                            <input type="text" class="form-control" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" value="{{ old('card_number') }}">
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Validade</label>
                                                <input type="text" class="form-control" name="card_expiry" placeholder="MM/AA" maxlength="5" value="{{ old('card_expiry') }}">
                                            </div>
                                            <div class="form-group">
                                                <label>CVV</label>
                                                <input type="text" class="form-control" name="card_cvv" placeholder="123" maxlength="4" value="{{ old('card_cvv') }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>CPF do Portador</label>
                                            <input type="text" class="form-control" name="card_cpf" placeholder="000.000.000-00" value="{{ old('card_cpf') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Opção Pagamento no Dia -->
                                <div class="payment-method" data-method="presencial">
                                    <label class="d-flex align-items-center">
                                        <input type="radio" name="forma_pagamento" value="presencial" {{ old('forma_pagamento') == 'presencial' ? 'checked' : '' }}>
                                        <div>
                                            <strong>Pagamento no Dia da Aula</strong>
                                            <p class="mb-0 text-muted">Pague diretamente ao professor no dia da aula</p>
                                        </div>
                                    </label>
                                    <div class="payment-details" id="presencial-details">
                                        <div class="alert alert-info">
                                            <h5>Pagamento Presencial</h5>
                                            <p>Você pagará diretamente ao professor no dia da aula.</p>
                                            <ul>
                                                <li>Aceito dinheiro, PIX ou cartão</li>
                                                <li>Confirme a forma de pagamento com o professor</li>
                                                <li>Em caso de cancelamento, avise com 24h de antecedência</li>
                                            </ul>
                                            <div class="form-group">
                                                <label>Status do Pagamento</label>
                                                <select class="form-control" name="status" id="presencial-status">
                                                    <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>Pendente (pagar no dia)</option>
                                                    <option value="RECEIVED" {{ old('status') == 'RECEIVED' ? 'selected' : '' }}>Confirmado (pago agora)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success btn-lg btn-block" id="confirm-button">
                                        <i class="fas fa-lock"></i> Confirmar Agendamento
                                    </button>
                                    <p class="text-center text-muted mt-2">
                                        <small><i class="fas fa-shield-alt"></i> Seus dados estão seguros e protegidos</small>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <x-detalhes-agendamento-confirm :model="$model"/>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Carregar dados do localStorage
            var diaDaSemana = localStorage.getItem('diaDaSemana');
            var data = localStorage.getItem('data');
            var horaDaAula = localStorage.getItem('horaDaAula');
            
            $('#data_aula').val(data);
            $('#hora_aula').val(horaDaAula);
            
            // Formatar data para exibição (de YYYY-MM-DD para DD/MM/YYYY)
            if (data) {
                var dataFormatada = formatarData(data);
                $('#display_data_aula').text(dataFormatada);
            }
            $('#display_hora_aula').text(horaDaAula);

            var servico = localStorage.getItem('servicos');
            if (servico) {
                var res = JSON.parse(servico);
                $('#valor_aula').val(res[0].preco);
                $('#titulo').val(res[0].titulo);
                $('#display_valor_aula').text(res[0].preco);
                $('#display_titulo').text(res[0].titulo);
            }

            // Manipular seleção de forma de pagamento
            $('.payment-method').on('click', function() {
                var method = $(this).data('method');
                
                // Marcar radio button
                $(this).find('input[type="radio"]').prop('checked', true);
                
                // Atualizar visual
                $('.payment-method').removeClass('selected');
                $(this).addClass('selected');
                
                // Mostrar detalhes da forma de pagamento
                $('.payment-details').removeClass('active');
                $('#' + method + '-details').addClass('active');
                
                // Atualizar campo hidden payment_method
                $('#payment_method').val(method);
                
                // Atualizar campo hidden status baseado no método
                if (method === 'presencial') {
                    $('#status').val($('#presencial-status').val());
                } else {
                    $('#status').val('PENDING');
                }
                
                // Atualizar texto do botão
                updateButtonText(method);
            });

            // Atualizar status quando o select mudar
            $('#presencial-status').on('change', function() {
                $('#status').val($(this).val());
            });

            // Formatação dos campos do cartão
            $('input[name="card_number"]').on('input', function() {
                var value = $(this).val().replace(/\s/g, '');
                var formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
                if (formattedValue.length > 19) formattedValue = formattedValue.substr(0, 19);
                $(this).val(formattedValue);
            });

            $('input[name="card_expiry"]').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length >= 2) {
                    value = value.substr(0, 2) + '/' + value.substr(2, 2);
                }
                $(this).val(value);
            });

            $('input[name="card_cvv"]').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                $(this).val(value);
            });

            $('input[name="card_cpf"]').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                $(this).val(value);
            });

            // Validação do formulário
            $('#payment-form').on('submit', function(event) {
                var data_aula = $('#data_aula').val();
                var hora_aula = $('#hora_aula').val();
                var valor_aula = $('#valor_aula').val();
                var titulo = $('#titulo').val();
                var payment_method = $('#payment_method').val();

                if (!data_aula || !hora_aula || !valor_aula || !titulo) {
                    alert('Dados do agendamento incompletos!');
                    event.preventDefault();
                    return;
                }

                if (!payment_method) {
                    alert('Por favor, selecione uma forma de pagamento!');
                    event.preventDefault();
                    return;
                }

                // Alterar a rota com base no método de pagamento
                if (payment_method === 'presencial') {
                    $(this).attr('action', "{{ route('empresa.pagamento.presencial') }}");
                } else {
                    $(this).attr('action', "{{ route('empresa.pagamento.asaas') }}");
                }

                // Validações específicas por forma de pagamento
                if (payment_method === 'cartao') {
                    var cardName = $('input[name="card_name"]').val();
                    var cardNumber = $('input[name="card_number"]').val().replace(/\s/g, '');
                    var cardExpiry = $('input[name="card_expiry"]').val();
                    var cardCvv = $('input[name="card_cvv"]').val();
                    var cardCpf = $('input[name="card_cpf"]').val();

                    if (!cardName || !cardNumber || !cardExpiry || !cardCvv || !cardCpf) {
                        alert('Por favor, preencha todos os dados do cartão!');
                        event.preventDefault();
                        return;
                    }

                    if (cardNumber.length !== 16) {
                        alert('Número do cartão deve ter 16 dígitos!');
                        event.preventDefault();
                        return;
                    }
                }

                // Mostrar spinner
                $('#spinner').show();
                $(this).find('button[type="submit"]').prop('disabled', true);
            });

            function updateButtonText(method) {
                var button = $('#confirm-button');
                switch(method) {
                    case 'pix':
                        button.html('<i class="fas fa-qrcode"></i> Gerar PIX e Confirmar');
                        break;
                    case 'cartao':
                        button.html('<i class="fas fa-credit-card"></i> Pagar com Cartão');
                        break;
                    case 'presencial':
                        button.html('<i class="fas fa-handshake"></i> Confirmar Agendamento');
                        break;
                    default:
                        button.html('<i class="fas fa-lock"></i> Confirmar Agendamento');
                }
            }

            // Função para formatar data de YYYY-MM-DD para DD/MM/YYYY
            function formatarData(data) {
                if (!data) return '';
                
                var partes = data.split('-');
                if (partes.length === 3) {
                    return partes[2] + '/' + partes[1] + '/' + partes[0];
                }
                return data;
            }
        });
    </script>
</x-public.layout>