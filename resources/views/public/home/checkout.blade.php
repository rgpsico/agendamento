<x-public.layout title="HOME">
	<style>
.error {
    color: red;
}
#spinner {
    position: fixed; 
    top: 50%; 
    left: 50%; 
    
    z-index: 9999; 
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
													<x-input-api-validation name="nome" col="6" placeholder="Ex: Fulando" label="Nome" value="" />

													<x-input-api-validation name="sobre_nome" col="6" placeholder="Ex: Silva" label="Sobrenome" value="" />

													<x-input-api-validation name="email" col="6" placeholder="Ex: exemplo@email.com" label="Email" value="" />

													<x-input-api-validation name="telefone" col="6" placeholder="Ex: (00) 12345-6789" label="Telefone" value="" />
											
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
													<x-input-api-validation name="nome_cartao" col="6" placeholder="Ex: Luiz Silva" label="Nome no Cartão"  value="" />												
													
													<x-input-api-validation name="numero_cartao" col="6" placeholder="1234  5678  9876  5432" label="Numero do Cartão" value="" />
													 
													<x-input-api-validation name="mes_vencimento" col="4" placeholder="Mês de vencimento" label="Mês de vencimento"  value="" />
													
													<x-input-api-validation name="ano_vencimento" col="4" placeholder="Ano Vencimento" label="Ano Vencimento" value="" />
													
													<x-input-api-validation name="cvv" col="4" placeholder="124" label="CVV"  value="" />
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
												<div id="spinner" class="spinner-border text-primary" role="status" style="display: none;">
													<span class="sr-only">Loading...</span>
												</div>
												<button type="submit" id="confirmarPagamento" class="btn btn-primary submit-btn" disabled>Confirmar Pagamento</button>
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

$(document).ready(function () {
  // Monitorando mudanças no checkbox
  $('#terms_accept').change(function () {
      if ($(this).is(':checked')) {
          $('#confirmarPagamento').prop('disabled', false); // Habilita o botão
      } else {
          $('#confirmarPagamento').prop('disabled', true); // Desabilita o botão
      }
  });
});


$("#paymentForm").on('submit', function(e) {

e.preventDefault(); 

$('#spinner').show();
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
if (response.error) {
  alert('Erro: ' + response.error); // Mostra o erro em um alerta
 
} else if (response && response.content && response.content.id) {
  const baseUrl = "{{ route('home.checkoutsucesso', ['id' => 'USER_ID']) }}";
  const redirectTo = baseUrl.replace('USER_ID', response.content.id);
  window.location.href = redirectTo;
}


  },
  error: function(response) {
try {
  console.log(response.responseJSON)
  let errors = response.responseJSON.errors;
  alert(response.responseJSON.error);
  $('#spinner').hide();
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

var token = result.token.id;


form.submit();
}
});
});

			</script>
			<!-- /Page Content -->
</x-layoutsadmin>