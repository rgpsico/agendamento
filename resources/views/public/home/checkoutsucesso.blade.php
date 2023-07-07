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
									<h3>Sua aula Foi Agendada com Sucesso!</h3>
									<p>O professor <strong>{{Str::ucfirst($nome_do_professor ?? 'AA')}}</strong><br> Vai te ensinar a Surfar no dia 
										<strong class='data_aula'>12 Nov 2019</strong>  <strong class='hora_aula'>12 Nov 2019</strong></p>
									<a href="" class="btn btn-primary view-inv-btn">Ver Recibo</a>
								</div>
							</div>
						</div>
						<!-- /Success Card -->
						
					</div>
				</div>
				
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
			<script>
				 var diaDaSemana = localStorage.getItem('diaDaSemana');
				var data = localStorage.getItem('data');
				var horaDaAula = localStorage.getItem('horaDaAula');
				$('.data_aula').text(data)
				$('.hora_aula').text(horaDaAula)
			</script>
			<!-- /Page Content -->
</x-layoutsadmin>