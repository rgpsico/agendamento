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
									<form action="{{ route('pagamento.stripe') }}" method="post" id="payment-form">
										@csrf
										<div class="info-widget">
											<div class="payment-widget">
												<h4 class="card-title">Metodos de Pagamento</h4>
									
												<input type="text" name="aluno_id" value="{{Auth::user()->id}}">
												<input type="text" name="professor_id" value="{{$model->user_id}}">
												<input type="text" name="modalidade_id" value="{{$model->user_id}}">
												<input type="hidden" id="data_aula" name="data_aula" value="">
												<input type="hidden" id="hora_aula" name="hora_aula" value="">
												<input type="hidden" id="valor_aula" name="valor_aula" value="">
												<input type="hidden" id="titulo" name="titulo" value="">
										
												
												
												<!-- Credit Card Payment -->
												<div class="payment-list">
													<label class="payment-radio credit-card-option">
														<input type="radio" name="radio" checked="">
														<span class="checkmark"></span>
														Cartão de credito
													</label>
									
													<!-- Stripe Elements Card input -->
													<div id="card-element"></div>
													<!-- Used to display Element errors. -->
													<div id="card-errors" role="alert"></div>
									
													<button class="btn btn-success my-5">Pagar</button>
												</div>
												<!-- /Credit Card Payment -->
												<!-- Other payment methods here -->
											</div>
										</div>
									</form>
									<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

									<script src="https://js.stripe.com/v3/"></script>
									<script>
										var stripe = Stripe('pk_test_51JDFv2BOmvZWJe0xeu2cwxUHl3Fw92cGWXoDlUpLQfJlY8K2yhk6LKs0GNtDP7GBmRgSs8aOySLTFlkAJJ7hb1Yr00q73EhugI');
										var elements = stripe.elements();
									
										var card = elements.create('card');
										card.mount('#card-element');
									
										card.addEventListener('change', function(event) {
											var displayError = document.getElementById('card-errors');
											if (event.error) {
												displayError.textContent = event.error.message;
											} else {
												displayError.textContent = '';
											}
										});
									
										var form = document.getElementById('payment-form');
										form.addEventListener('submit', function(event) {
											event.preventDefault();
									
											stripe.createToken(card).then(function(result) {
												if (result.error) {
													// Inform the user if there was an error
													var errorElement = document.getElementById('card-errors');
													errorElement.textContent = result.error.message;
												} else {
													// Send the token to your server
													stripeTokenHandler(result.token);
												}
											});
										});
									
										function stripeTokenHandler(token) {
											var form = document.getElementById('payment-form');
											var hiddenInput = document.createElement('input');
											hiddenInput.setAttribute('type', 'hidden');
											hiddenInput.setAttribute('name', 'stripeToken');
											hiddenInput.setAttribute('value', token.id);
											form.appendChild(hiddenInput);
									
											form.submit();
										}

										$(document).ready(function() {
											var diaDaSemana = localStorage.getItem('diaDaSemana');
											var data = localStorage.getItem('data');
											var horaDaAula = localStorage.getItem('horaDaAula');
											
											$('#data_aula').val(data);
											$('#hora_aula').val(horaDaAula);

											var servico = localStorage.getItem('servicos');
											if(servico)
											{
												var res = JSON.parse(servico)	
																					
												$('#valor_aula').val(res[0].preco);
												$('#titulo').val(res[0].titulo);
											}
										
											
    });
									</script>
									
									
											<!-- /Submit Section -->
											
										</div>
								
									<!-- /Checkout Form -->
									
								</div>
							
							
						</div>
						
						
						<x-detalhes-agendamento-confirm :model="$model"/>
				</div>

			</div>
			<!-- /Page Content -->
</x-layoutsadmin>