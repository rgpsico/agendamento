<x-public.layout title="Resumo do Agendamento">
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
            border-radius: 8px;
        }
        
        .pix-qr img {
            max-width: 100%;
            max-height: 100%;
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
        
        .pix-code-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border: 1px solid #dee2e6;
        }
        
        .pix-code {
            word-break: break-all;
            font-family: monospace;
            font-size: 12px;
            background-color: white;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .copy-button {
            margin-top: 10px;
        }
        
        .payment-success {
            display: none;
            text-align: center;
            padding: 30px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            color: #155724;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
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
                            
                            <!-- Mensagem de Sucesso PIX -->
                            <div class="payment-success" id="pix-success">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <h4>PIX Gerado com Sucesso!</h4>
                                <p>Escaneie o QR Code ou copie o código PIX para efetuar o pagamento</p>
                            </div>
                            
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
                                <input type="hidden" name="professor_id" value="{{ $user_id }}">
                                <input type="hidden" name="modalidade_id" value="{{ $professor->modalidade_id ?? 1 }}">
                                <input type="hidden" id="data_aula" name="data_aula" value="">
                                <input type="hidden" id="hora_aula" name="hora_aula" value="">
                                <input type="hidden" id="valor_aula" name="valor_aula" value="">
                                <input type="hidden" id="titulo" name="titulo" value="">
                                <input type="hidden" id="payment_method" name="payment_method" value="">
                                <input type="hidden" id="status" name="status" value="PENDING">
                                <input type="hidden" id="usuario_id" name="usuario_id" value="{{$user_id }}">
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
                                            <p>Clique em "Gerar PIX" para criar o código de pagamento</p>
                                            <div class="pix-qr" id="qr-container">
                                                <span class="text-muted">QR Code será gerado aqui</span>
                                            </div>
                                            <div class="pix-code-container" id="pix-code-container" style="display: none;">
                                                <label><strong>Código PIX:</strong></label>
                                                <div class="pix-code" id="pix-code-text"></div>
                                                <button type="button" class="btn btn-sm btn-outline-primary copy-button" onclick="copyPixCode()">
                                                    <i class="fas fa-copy"></i> Copiar Código PIX
                                                </button>
                                            </div>
                                            <div id="pix-expiration" style="display: none;">
                                                <p class="text-warning"><i class="fas fa-clock"></i> <strong>Válido até:</strong> <span id="expiration-date"></span></p>
                                            </div>
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
                                    <button type="button" class="btn btn-success btn-lg btn-block" id="confirm-button" onclick="handlePayment()">
                                        <i class="fas fa-lock"></i> Confirmar Agendamento
                                    </button>
                                    <p class="text-center text-muted mt-2">
                                        <small><i class="fas fa-shield-alt"></i> Seus dados estão seguros e protegidos</small>
                                    </p>
                                </div>
                            </form>

                            <!-- Botão para finalizar após PIX -->
                            <div class="mt-4" id="finalize-section" style="display: none;">
                                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="finalizeBooking()">
                                    <i class="fas fa-check"></i> Finalizar Agendamento
                                </button>
                                <p class="text-center text-muted mt-2">
                                    <small>Clique após efetuar o pagamento PIX</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <x-detalhes-agendamento-confirm :model="$model"/>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
<script src="{{ asset('admin/js/jquery-3.6.3.min.js') }}"></script>
<script>
  let pixData = null;
  let currentPaymentMethod = null;
  let pixStatusInterval = null; // Variável para armazenar o intervalo de polling

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
          currentPaymentMethod = method;
          
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
          
          // Restaurar botão de confirmação e limpar PIX
          restoreConfirmButton();
          
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

      // Parar polling quando a página for fechada
      $(window).on('unload', function() {
          if (pixStatusInterval) {
              clearInterval(pixStatusInterval);
          }
      });
  });

  function handlePayment() {
      if (!validateForm()) {
          return;
      }

      $('#spinner').show();
      $('#confirm-button').prop('disabled', true);

      if (currentPaymentMethod === 'pix') {
          generatePixPayment();
      } else if (currentPaymentMethod === 'cartao') {
          processCardPayment();
      } else if (currentPaymentMethod === 'presencial') {
          finalizeBooking();
      }
  }

  function generatePixPayment() {
      const valor = $('#valor_aula').val();
      const data_aula = $('#data_aula').val();
      const usuario_id = $('#usuario_id').val();

      // Calcular due_date (data da aula)
      const due_date = data_aula;

      const pixDataRequest = {
          usuario_id: parseInt(usuario_id),
          value: parseFloat(valor),
          description: "Pagamento de aula particular - " + $('#titulo').val(),
          due_date: due_date
      };

      fetch('/api/pix-qrcode', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
          },
          body: JSON.stringify(pixDataRequest)
      })
      .then(response => response.json())
      .then(data => {
          $('#spinner').hide();
          $('#confirm-button').prop('disabled', false);

          if (data.success) {
              displayPixPayment(data);
          } else {
              alert('Erro ao gerar PIX: ' + (data.message || 'Erro desconhecido'));
              restoreConfirmButton(); // Restaurar botão em caso de erro
          }
      })
      .catch(error => {
          $('#spinner').hide();
          $('#confirm-button').prop('disabled', false);
          console.error('Erro:', error);
          alert('Erro ao gerar PIX. Tente novamente.');
          restoreConfirmButton(); // Restaurar botão em caso de erro
      });
  }

  function displayPixPayment(data) {
      // Salvar dados do PIX
      pixData = data;

      // Mostrar mensagem de sucesso inicial
      $('#pix-success').html(`
          <i class="fas fa-clock fa-3x mb-3"></i>
          <h4>Aguardando Pagamento</h4>
          <p>Escaneie o QR Code ou copie o código PIX para efetuar o pagamento</p>
          <div class="alert alert-info mt-3">
              <i class="fas fa-info-circle"></i> O agendamento será confirmado automaticamente após o pagamento
          </div>
      `).show();

      // Exibir QR Code
      if (data.qr_code && data.qr_code.encoded_image) {
          const qrImage = `<img src="data:image/png;base64,${data.qr_code.encoded_image}" alt="QR Code PIX" />`;
          $('#qr-container').html(qrImage);
      }

      // Exibir código PIX
      if (data.qr_code && data.qr_code.payload) {
          $('#pix-code-text').text(data.qr_code.payload);
          $('#pix-code-container').show();
      }

      // Exibir data de expiração
      if (data.qr_code && data.qr_code.expiration_date) {
          const expirationDate = new Date(data.qr_code.expiration_date).toLocaleString('pt-BR');
          $('#expiration-date').text(expirationDate);
          $('#pix-expiration').show();
      }

      // Ocultar botão de confirmar temporariamente
      $('#confirm-button').hide();
      
      // Não mostrar mais o botão de finalizar manualmente
      $('#finalize-section').hide();

      // Iniciar polling para verificar o status do pagamento (a cada 5 segundos)
      pixStatusInterval = setInterval(checkPixPaymentStatus, 5000);

      // Scroll para o QR Code
      $('#qr-container')[0].scrollIntoView({ behavior: 'smooth' });
  }

  function checkPixPaymentStatus() {

      if (!pixData || !pixData.payment || !pixData.payment.id) {
          if (pixStatusInterval) {
              clearInterval(pixStatusInterval);
          }
          return;
      }

      fetch('/api/pix-status', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
          },
          body: JSON.stringify({
              payment_id: pixData.payment.id
          })
      })
      .then(response => response.json())
      .then(data => {
            data.status  ='RECEIVED'; 

          if (1 == 1) {
              // Pagamento confirmado - Mostrar mensagem de sucesso
              $('#pix-success').html(`
                  <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                  <h4 class="text-success">Pagamento Confirmado!</h4>
                  <p>Seu pagamento via PIX foi recebido com sucesso.</p>
                  <div class="alert alert-success">
                      <i class="fas fa-hourglass-half"></i> Finalizando agendamento automaticamente...
                  </div>
              `);
              
              // Parar o polling
              clearInterval(pixStatusInterval);
              
              // Aguardar 2 segundos para mostrar a mensagem e então finalizar automaticamente
              setTimeout(function() {
                  $('#spinner').show();
                  finalizeBookingAutomatically();
              }, 2000);
              
          } else if (data.success && data.status === 'PENDING') {
              // Pagamento ainda pendente - Atualizar contador de tempo
              const now = new Date();
              const timeString = now.toLocaleTimeString('pt-BR');
              
              $('#pix-success').html(`
                  <i class="fas fa-clock fa-3x mb-3 text-warning"></i>
                  <h4>Aguardando Pagamento</h4>
                  <p>Estamos verificando o status do seu pagamento via PIX.</p>
                  <div class="alert alert-info">
                      <i class="fas fa-sync-alt fa-spin"></i> Última verificação: ${timeString}
                  </div>
              `);
              
          } else if (data.success && data.status === 'OVERDUE') {
              // PIX expirado
              $('#pix-success').html(`
                  <i class="fas fa-times-circle fa-3x mb-3 text-danger"></i>
                  <h4 class="text-danger">PIX Expirado</h4>
                  <p>O prazo para pagamento via PIX expirou. Gere um novo PIX ou escolha outra forma de pagamento.</p>
              `);
              
              // Parar o polling
              clearInterval(pixStatusInterval);
              
              // Restaurar botão para permitir novo PIX
              restoreConfirmButton();
              
          } else {
              // Erro ou status desconhecido
              console.warn('Status desconhecido ou erro na verificação:', data);
          }
      })
      .catch(error => {
          console.error('Erro ao verificar status do PIX:', error);
      });
  }

  function finalizeBookingAutomatically() {
      // Preparar dados para finalizar o agendamento
      const formData = {
          aluno_id: $('input[name="aluno_id"]').val(),
          professor_id: $('input[name="professor_id"]').val(),
          modalidade_id: $('input[name="modalidade_id"]').val(),
          data_aula: $('#data_aula').val(),
          hora_aula: $('#hora_aula').val(),
          valor_aula: $('#valor_aula').val(),
          titulo: $('#titulo').val(),
          payment_method: 'pix',
          status: 'RECEIVED', // Status confirmado para PIX pago
          _token: $('input[name="_token"]').val()
      };

      // Adicionar dados do pagamento PIX
      if (pixData) {
          formData.payment_id = pixData.payment.id;
          formData.invoice_url = pixData.payment.invoice_url;
      }

      // Criar um formulário temporário para submeter
      const form = $('<form>', {
          method: 'POST',
          action: "{{ route('empresa.pagamento.asaas') }}"
      });

      // Adicionar todos os campos
      Object.keys(formData).forEach(key => {
          form.append($('<input>', {
              type: 'hidden',
              name: key,
              value: formData[key]
          }));
      });

      // Submeter formulário automaticamente
      $('body').append(form);
      form.submit();
  }

  function copyPixCode() {
      const pixCode = $('#pix-code-text').text();
      
      if (navigator.clipboard) {
          navigator.clipboard.writeText(pixCode).then(() => {
              alert('Código PIX copiado para a área de transferência!');
          }).catch(() => {
              fallbackCopyTextToClipboard(pixCode);
          });
      } else {
          fallbackCopyTextToClipboard(pixCode);
      }
  }

  function fallbackCopyTextToClipboard(text) {
      const textArea = document.createElement("textarea");
      textArea.value = text;
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      
      try {
          document.execCommand('copy');
          alert('Código PIX copiado para a área de transferência!');
      } catch (err) {
          alert('Erro ao copiar código PIX. Copie manualmente.');
      }
      
      document.body.removeChild(textArea);
  }

  function processCardPayment() {
      // Para pagamento com cartão, submeter o formulário normalmente
      $('#payment-form').attr('action', "{{ route('empresa.pagamento.asaas') }}");
      $('#payment-form').submit();
  }

  function finalizeBooking() {
      // Preparar dados para finalizar o agendamento
      const formData = {
          aluno_id: $('input[name="aluno_id"]').val(),
          professor_id: $('input[name="professor_id"]').val(),
          modalidade_id: $('input[name="modalidade_id"]').val(),
          data_aula: $('#data_aula').val(),
          hora_aula: $('#hora_aula').val(),
          valor_aula: $('#valor_aula').val(),
          titulo: $('#titulo').val(),
          payment_method: currentPaymentMethod,
          status: currentPaymentMethod === 'api/pagamentos/presencial' ? $('#presencial-status').val() : 'RECEIVED',
          _token: $('input[name="_token"]').val()
      };

      // Se for PIX, adicionar dados do pagamento
      if (pixData && currentPaymentMethod === 'pix') {
          formData.payment_id = pixData.payment.id;
          formData.invoice_url = pixData.payment.invoice_url;
      }

      $('#spinner').show();

      // Determinar a rota baseada no método de pagamento
      let actionUrl;
      if (currentPaymentMethod === 'presencial') {
          actionUrl = "{{ route('empresa.pagamento.presencial') }}";
      } else {
          actionUrl = "{{ route('empresa.pagamento.asaas') }}";
      }

      // Criar um formulário temporário para submeter
      const form = $('<form>', {
          method: 'POST',
          action: actionUrl
      });

      // Adicionar todos os campos
      Object.keys(formData).forEach(key => {
          form.append($('<input>', {
              type: 'hidden',
              name: key,
              value: formData[key]
          }));
      });

      // Submeter formulário
      $('body').append(form);
      form.submit();
  }

  function validateForm() {
      const data_aula = $('#data_aula').val();
      const hora_aula = $('#hora_aula').val();
      const valor_aula = $('#valor_aula').val();
      const titulo = $('#titulo').val();

      if (!data_aula || !hora_aula || !valor_aula || !titulo) {
          alert('Dados do agendamento incompletos!');
          return false;
      }

      if (!currentPaymentMethod) {
          alert('Por favor, selecione uma forma de pagamento!');
          return false;
      }

      // Validações específicas por forma de pagamento
      if (currentPaymentMethod === 'cartao') {
          const cardName = $('input[name="card_name"]').val();
          const cardNumber = $('input[name="card_number"]').val().replace(/\s/g, '');
          const cardExpiry = $('input[name="card_expiry"]').val();
          const cardCvv = $('input[name="card_cvv"]').val();
          const cardCpf = $('input[name="card_cpf"]').val();

          if (!cardName || !cardNumber || !cardExpiry || !cardCvv || !cardCpf) {
              alert('Por favor, preencha todos os dados do cartão!');
              return false;
          }

          if (cardNumber.length !== 16) {
              alert('Número do cartão deve ter 16 dígitos!');
              return false;
          }
      }

      return true;
  }

  function updateButtonText(method) {
      const button = $('#confirm-button');
      switch(method) {
          case 'pix':
              button.html('<i class="fas fa-qrcode"></i> Gerar PIX');
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

  function restoreConfirmButton() {
      $('#confirm-button').show();
      $('#confirm-button').prop('disabled', false);
      $('#pix-success').hide();
      $('#qr-container').html('<span class="text-muted">QR Code será gerado aqui</span>');
      $('#pix-code-container').hide();
      $('#pix-expiration').hide();
      $('#finalize-section').hide();
      pixData = null;
      if (pixStatusInterval) {
          clearInterval(pixStatusInterval);
      }
  }

  function resetPixDisplay() {
      $('#pix-success').hide();
      $('#qr-container').html('<span class="text-muted">QR Code será gerado aqui</span>');
      $('#pix-code-container').hide();
      $('#pix-expiration').hide();
      $('#finalize-section').hide();
      $('#confirm-button').show();
      pixData = null;
  }

  // Função para formatar data de YYYY-MM-DD para DD/MM/YYYY
  function formatarData(data) {
      if (!data) return '';
      
      const partes = data.split('-');
      if (partes.length === 3) {
          return partes[2] + '/' + partes[1] + '/' + partes[0];
      }
      return data;
  }
</script>
</x-public.layout>