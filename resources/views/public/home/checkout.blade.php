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
												   <input type="hidden" name="professor_id" id="professor_id" value="{{$model->user_id}}">	
													<x-input-api-validation name="nome" col="6" placeholder="Ex: Roger Silva" label="Nome" value="Roger Neves" />

													<x-input-api-validation name="sobre_nome" col="6" placeholder="Ex: Neves" label="Sobrenome" value="Neves" />

													<x-input-api-validation name="email" col="6" placeholder="Ex: exemplo@email.com" label="Email" value="rgyr2010@hotmail.com" />

													<x-input-api-validation name="telefone" col="6" placeholder="Ex: (00) 12345-6789" label="Telefone" value="21 990271287" />
											
													<div class="exist-customer my-4 mb-4">
														Sou cliente? 
														<a href="{{route('home.login')}}" >Click aqui para fazer login</a>
													</div>
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
													<x-input-api-validation name="nome_cartao" col="6" placeholder="Ex: Roger Silva" label="Nome no Cartão"  value="Roger Neves" />												
													
													<x-input-api-validation name="numero_cartao" col="6" placeholder="1234  5678  9876  5432" label="Numero do Cartão" value="4242 4242 4242 4242" />
													 
													<x-input-api-validation name="mes_vencimento" col="4" placeholder="Mês de vencimento" label="Mês de vencimento"  value="12" />
													
													<x-input-api-validation name="ano_vencimento" col="4" placeholder="Ano Vencimento" label="Ano Vencimento" value="28" />
													
													<x-input-api-validation name="cvv" col="4" placeholder="124" label="CVV"  value="124" />
												</div>
											</div>
											<!-- /Credit Card Payment -->
											<!-- Paypal Payment -->
											<div class="payment-list">
												{{-- <label class="payment-radio paypal-option">
													<input type="radio" name="radio">
													<span class="checkmark"></span>
													Paypal
												</label> --}}
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
    e.preventDefault(); 

    
    var formData = $(this).serializeArray(); 

    
	var servicos = localStorage.getItem('servicos');
	var servicosArray = JSON.parse(servicos); 	 
	var total = 0;

	for (var i = 0; i < servicosArray.length; i++) {
		total += parseFloat(servicosArray[i].preco);
	}

	var data = localStorage.getItem('data');
	var horaAula = localStorage.getItem('horaDaAula');

	formData.push({ name: "servicos", value: servicosArray });
	formData.push({ name: "total", value: total });
	formData.push({ name: "data_aula", value: data });
	formData.push({ name: "hora_aula", value: horaAula });
		
    var data = {};
	
    $(formData).each(function(index, obj){
        data[obj.name] = obj.value;
    });

    // Faz a solicitação POST para a API
    $.ajax({
        url: '/pagamento', // A URL da API que você está chamando
        type: 'post', // O tipo de solicitação que você está fazendo (GET, POST, etc.)
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Para proteção contra falsificação de solicitação entre sites (CSRF)
        },
        data: data, // Os dados que você está enviando para a API
        success: function (response) {
			console.log(response)
          
	       
		if(response && response.content && response.content.id) {
            const baseUrl = "{{ route('home.checkoutsucesso', ['id' => 'USER_ID']) }}";
            const redirectTo = baseUrl.replace('USER_ID', response.content.id);
            window.location.href = redirectTo;
        }

			
        },
        error: function(response) {
			try {

				let errors = response.responseJSON.errors;
				
			$('.error').empty()	
			$.each(errors, function(key, values) {
				$('#' + key ).removeClass(); 	
			});

			$.each(errors, function(key, values) {
			let errorMessages = '';

    		$('#' + key + '_erro').empty(); 			
			


            $.each(values, function(index, value) {
                errorMessages += '<span class="error">' + value + '</span><br>';
            });
			
			$('#' + key).addClass('is-invalid');
			$('#' + key+'_erro').show()
            $('#' + key+'_erro').append(errorMessages);
        });
				
			} catch (error) {
				
			}
      

    }
    });
});

// Limpa a mensagem de erro ao corrigir o campo


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