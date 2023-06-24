<x-public.layout title="HOME">
	<style>
		.error {
    color: red;
}
	</style>
   
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
									<script src="https://js.stripe.com/v3/"></script>
									<div id="payment-form"></div>
									<form id="paymentForm" action="{{route('agendamento.pagamento')}}" method="POST" id="">
										@csrf
										<div class="info-widget">
											<h4 class="card-title">Informações</h4>
											<div class="row">
												<div class="col-md-6 col-sm-12">
													<div class="form-group card-label">
														<label>Nome</label>
														<input class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" type="text">
													     <div class="invalid-feedback" style='none' id="nome_erro"></div>
													
													</div>
												</div>
												<div class="col-md-6 col-sm-12">
													<div class="form-group card-label">
														<label>Sobre Nome</label>
														<input type="text" class="form-control" id="sobre_nome" name="sobre_nome" value="{{ old('sobre_nome') }}" ">
														<div class="invalid-feedback"  id="sobre_nome_erro"></div>
													
													</div>
												</div>
												<div class="col-md-6 col-sm-12">
													<div class="form-group card-label">
														<label>Email</label>
														<input class="form-control" id="email" name="email" value="{{ old('email') }}" type="email">
														 <div class="invalid-feedback"  id="email_erro"></div>
														
													</div>
												</div>
												<div class="col-md-6 col-sm-12">
													<div class="form-group card-label">
														<label>Telefone</label>
														<input class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone') }}" type="text">
														<div class="invalid-feedback"  id="telefone_erro"></div>
													
													</div>
												
											</div>
											<div class="exist-customer">Sou cliente? <a href="#">Click aqui para fazer login</a></div>
										</div>
										<!-- /Personal Information -->
									
										<div class="payment-widget">
											<h4 class="card-title">Metodos de Pagamento</h4>
									
											<!-- Credit Card Payment -->
										
											<div class="payment-list" id="">
												{{-- <label class="payment-radio credit-card-option">
													<input type="radio" name="radio" checked="">
													<span class="checkmark"></span>
													Cartão de credito
													
												</label> --}}
												
												<div class="row">
													
												
													<x-input-api-validation name="nome_cartao" col="6" placeholder="Ex: Roger Silva" label="Nome no Cartão" />												
													
													<x-input-api-validation name="numero_cartao" col="6" placeholder="1234  5678  9876  5432" label="Numero do Cartão" />
													 
													<x-input-api-validation name="mes_vencimento" col="4" placeholder="Mês de vencimento" label="Mês de vencimento" />
													
													<x-input-api-validation name="ano_vencimento" col="4" placeholder="Ano Vencimento" label="Ano Vencimento" />
													
													
													<x-input-api-validation name="cvv" col="4" placeholder="124" label="CVV" />
													
													
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
        error: function(response) {
        let errors = response.responseJSON.errors;
        $.each(errors, function(key, values) {
            let errorMessages = '';
            $.each(values, function(index, value) {
                errorMessages += '<span class="error">' + value + '</span><br>';
            });
			
			$('#' + key).addClass('is-invalid');
			$('#' + key+'_erro').show()
            $('#' + key+'_erro').append(errorMessages);
        });
    }
    });
});


// Configure sua chave pública do Stripe
var stripe = Stripe('pk_test_51JDFv2BOmvZWJe0xeu2cwxUHl3Fw92cGWXoDlUpLQfJlY8K2yhk6LKs0GNtDP7GBmRgSs8aOySLTFlkAJJ7hb1Yr00q73EhugI');
								

// Selecione o formulário de pagamento
var form = document.getElementById('payment-form');

// Adicione um manipulador de eventos para o envio do formulário
form.addEventListener('submit', function(event) {
  event.preventDefault();

  // Crie o token do Stripe ao enviar o formulário
  stripe.createToken('card', {
    name: document.getElementById('card_name').value
    // Outros detalhes do cartão, se necessário
  }).then(function(result) {
    if (result.error) {
      // Se houver um erro ao criar o token, exiba a mensagem de erro
      console.log(result.error.message);
    } else {
      // Se o token for criado com sucesso, você pode acessar o token através de result.token.id
      var token = result.token.id;

      // Faça o que você precisa com o token (por exemplo, enviar para o backend para criar uma cobrança)
      // ...

      // Envie o formulário para o backend (ou faça qualquer outra ação desejada)
      form.submit();
    }
  });
});

			</script>
			<!-- /Page Content -->
</x-layoutsadmin>