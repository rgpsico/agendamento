<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="TESTE"/>
			<!-- /Breadcrumb -->
			<div class="content" style="transform: none; min-height: 172.906px;">
				<div class="container" style="transform: none;">

					<div class="row" style="transform: none;">
						<div class="col-md-7 col-lg-8">
							<div class="card">
								<div class="card-body">
								<x-alert/>
									<!-- Checkout Form -->
									<form id="paymentForm" action="{{route('agendamento.pagamento')}}" method="POST">
										@csrf
										<div class="info-widget">
											<h4 class="card-title">Informações</h4>
											<div class="row">
												<div class="col-md-6 col-sm-12">
													<div class="form-group card-label">
														<label>Nome</label>
														<input class="form-control @error('nome') is-invalid @enderror" name="nome" value="{{ old('nome') }}" type="text">
														@error('nome')
															<div class="invalid-feedback">{{ $message }}</div>
														@enderror
													</div>
												</div>
												<div class="col-md-6 col-sm-12">
													<div class="form-group card-label">
														<label>Sobre Nome</label>
														<input class="form-control @error('sobre_nome') is-invalid @enderror" name="sobre_nome" value="{{ old('sobre_nome') }}" type="text">
														@error('sobre_nome')
															<div class="invalid-feedback">{{ $message }}</div>
														@enderror
													</div>
												</div>
												<div class="col-md-6 col-sm-12">
													<div class="form-group card-label">
														<label>Email</label>
														<input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" type="email">
														@error('email')
															<div class="invalid-feedback">{{ $message }}</div>
														@enderror
													</div>
												</div>
												<div class="col-md-6 col-sm-12">
													<div class="form-group card-label">
														<label>Telefone</label>
														<input class="form-control @error('telefone') is-invalid @enderror" name="telefone" value="{{ old('telefone') }}" type="text">
														@error('telefone')
															<div class="invalid-feedback">{{ $message }}</div>
														@enderror
													</div>
												
											</div>
											<div class="exist-customer">Sou cliente? <a href="#">Click aqui para fazer login</a></div>
										</div>
										<!-- /Personal Information -->
									
										<div class="payment-widget">
											<h4 class="card-title">Metodos de Pagamento</h4>
									
											<!-- Credit Card Payment -->
											<div class="payment-list">
												<label class="payment-radio credit-card-option">
													<input type="radio" name="radio" checked="">
													<span class="checkmark"></span>
													Cartão de credito
												</label>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group card-label">
															<label for="card_name">Nome no Cartão</label>
															<input class="form-control @error('nome_cartao') is-invalid @enderror" name="nome_cartao" id="card_name" value="{{ old('nome_cartao') }}" type="text">
															@error('nome_cartao')
																<div class="invalid-feedback">{{ $message }}</div>
															@enderror
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group card-label">
															<label for="card_number">Numero do Cartão</label>
															<input 
															class="form-control @error('numero_cartao') is-invalid @enderror" 
															name="numero_cartao" 
															id="card_number" 
															value="{{ old('numero_cartao') }}" 
															placeholder="1234  5678  9876  5432" 
															type="text">
															@error('numero_cartao')
																<div class="invalid-feedback">{{ $message }}</div>
															@enderror
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group card-label">
															<label for="expiry_month">Mês de vencimento</label>
															<input class="form-control @error('mes_vencimento') is-invalid @enderror" name="mes_vencimento" id="expiry_month" value="{{ old('mes_vencimento') }}" placeholder="MM" type="text">
															@error('mes_vencimento')
																<div class="invalid-feedback">{{ $message }}</div>
															@enderror
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group card-label">
															<label for="expiry_year">Ano de Vencimento </label>
															<input class="form-control @error('ano_vencimento') is-invalid @enderror" name="ano_vencimento" id="expiry_year" value="{{ old('ano_vencimento') }}" placeholder="YY" type="text">
															@error('ano_vencimento')
																<div class="invalid-feedback">{{ $message }}</div>
															@enderror
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group card-label">
															<label for="cvv">CVV</label>
															<input class="form-control @error('cvv') is-invalid @enderror" name="cvv" id="cvv" value="{{ old('cvv') }}" type="text">
															@error('cvv')
																<div class="invalid-feedback">{{ $message }}</div>
															@enderror
														</div>
													</div>
												</div>
											</div>
											<!-- /Credit Card Payment -->
											<!-- Paypal Payment -->
											<div class="payment-list">
												<label class="payment-radio paypal-option">
													<input type="radio" name="radio">
													<span class="checkmark"></span>
													Paypal
												</label>
											</div>
											<!-- /Paypal Payment -->
											<!-- Terms Accept -->
											<div class="terms-accept">
												<div class="custom-checkbox">
													<input type="checkbox" id="terms_accept" name="terms_accept">
													<label for="terms_accept">Eu aceito os termos 
														<a href="#">Termos &amp; Condição</a></label>
												</div>
											</div>
											<!-- /Terms Accept -->
											<!-- Submit Section -->
											<div class="submit-section mt-4">
												<button type="submit"  class="btn btn-primary submit-btn">Confirmar Pagamento</button>
											</div>
										</div>
									</form>
									
											<!-- /Submit Section -->
											
										</div>
								
									<!-- /Checkout Form -->
									
								</div>
							</div>
							
						</div>
						
						
						<x-detalhes-agendamento-confirm :model="$model" />
				</div>

			</div>
			<script>
	$("#paymentForm").on('submit', function(e) {
    e.preventDefault(); // para impedir o envio padrão do formulário

    // Pega os dados do formulário
    var formData = $(this).serializeArray(); 

    // Adiciona os dados do localStorage
    var servicos = localStorage.getItem('servicos');
    formData.push({name: "servicos", value: servicos});
    
    // Converta formData em um objeto para podermos manipulá-lo facilmente
    var data = {};
    $(formData).each(function(index, obj){
        data[obj.name] = obj.value;
    });

    // Faz a solicitação POST para a API
    $.ajax({
        url: '/api/pagamento', // A URL da API que você está chamando
        type: 'post', // O tipo de solicitação que você está fazendo (GET, POST, etc.)
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Para proteção contra falsificação de solicitação entre sites (CSRF)
        },
        data: data, // Os dados que você está enviando para a API
        success: function (response) {
            // O que acontecerá se a chamada à API for bem-sucedida
            console.log(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // O que acontecerá se a chamada à API falhar
            console.log(textStatus, errorThrown);
        }
    });
});

			</script>
			<!-- /Page Content -->
</x-layoutsadmin>