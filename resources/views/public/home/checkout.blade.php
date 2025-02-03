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
			<script src="{{asset('js/checkout.js')}}"></script>
			<!-- /Page Content -->
</x-layoutsadmin>