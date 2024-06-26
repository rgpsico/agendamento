<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="TESTE"/>
			<!-- /Breadcrumb -->
			<div class="container">
				
				<div class="row justify-content-center">
					<div class="col-lg-6">
					
						<!-- Success Card -->
						<div class="card success-card">
							<div class="card-body">
								<div class="success-cont">
									<i class="fas fa-check"></i>
									<h3>Sua aula foi agendada com sucesso!</h3>
									<p>
										O professor <strong>{{ Str::ucfirst($professor->usuario->nome ?? 'Professor') }}</strong>
										vai ensinar a surfar no dia 
										<strong class='data_aula'></strong>
										às <strong class='hora_aula'></strong>
									</p>
									<div class="col-12">
										<a href="" class="btn btn-primary view-inv-btn">Ver Recibo</a>
									</div>

									<div class="col-12 my-2">
										<a href="https://wa.me/{{ $professor->usuario->telefone }}" target="_blank" class="btn">
											<i class="fab fa-whatsapp"></i> Falar com o Professor
										</a>
									</div>
								
									
								</div>
							</div>
						</div>
						
						
						
						<!-- /Success Card -->
						
					</div>
				</div>
				
			</div>
			<script src="{{asset('admin/js/jquery-3.6.3.min.js')}}"></script>
			<script>
				$(document)
				 var diaDaSemana = localStorage.getItem('diaDaSemana');
				var data = localStorage.getItem('data');
				var horaDaAula = localStorage.getItem('horaDaAula');
				
				$('.data_aula').text(data)
				$('.hora_aula').text(horaDaAula)
			</script>
			<!-- /Page Content -->
</x-layoutsadmin>