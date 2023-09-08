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
										O professor <strong>{{ Str::ucfirst($professor->usuario->nome) }}</strong>
										vai ensinar  a surfar no dia 
										<strong class='data_aula'>{{ date('d/m/Y', strtotime($agendamento->data_da_aula)) }}</strong>
										às <strong class='hora_aula'>{{ date('H:i', strtotime($agendamento->data_da_aula)) }}</strong>
									</p>
									<a href="" class="btn btn-primary view-inv-btn">Ver Recibo</a>
								</div>
							</div>
						</div>
						
						
						<!-- /Success Card -->
						
					</div>
				</div>
				
			</div>
			<script src="{{asset('admin/js/jquery-3.6.3.min.js')}}jquery.min.js"></script>
			<script>
				 var diaDaSemana = localStorage.getItem('diaDaSemana');
				var data = localStorage.getItem('data');
				var horaDaAula = localStorage.getItem('horaDaAula');
				$('.data_aula').text(data)
				$('.hora_aula').text(horaDaAula)
			</script>
			<!-- /Page Content -->
</x-layoutsadmin>