<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="TESTE"/>
			<!-- /Breadcrumb -->


			@include('admin.empresas._partials.modal')
			<div class="container">
			<div class="row">
				<div class="col-12">
					{{-- <input type="text" id="aluno_id" value="{{Auth::user()->id ?? ''}}">
					<input type="text" id="aula_id" value="">
					<input type="text" id="professor_id"> --}}
					<div class="card">
						<div class="card-body">
						
						</div>
					</div>
				

		
					<!-- Schedule Widget -->
					<div class="card booking-schedule schedule-widget">					
						<!-- Schedule Header -->
						<div class="schedule-header">
							<div class="row">
								<div class="col-md-12">
								
									<!-- Day Slot -->
									<div class="day-slot">
									
									</div>
									<!-- /Day Slot -->
									
								</div>
							</div>
						</div>
						<!-- /Schedule Header -->
						
						<!-- Schedule Content -->
						<div class="schedule-cont">
							
						</div>
						<!-- /Schedule Content -->
						
					</div>
					<!-- /Schedule Widget -->
					
					<!-- Submit Section -->
					<div class="submit-section proceed-btn text-end">
						<a href="{{route('home.checkout',['id' =>1])}}" 
							class="btn btn-primary submit-btn">Agendar e Pagar</a>
					</div>
					<!-- /Submit Section -->
					
				</div>
			</div>
		</div>
			<!-- /Page Content -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

			<script>
		


var form = 
`<form>
                    <div class="row form-row">
						<input type="text" id="professor_id" class="form-control" value="">
						<input type="text" id="user_id" class="form-control" value="">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Data da Aula</label>
                                <input type="date" id="data_aula" class="form-control" value="John">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label>Hora</label>
                                <input type="time" id="hora" class="form-control hora" value="">
                            </div>
                        </div>
                       <button type="submit" class="btn btn-primary w-100">Agendar Aula</button>
                </form>`;

$(".agendarAulas").on("click", function(e) {
	e.preventDefault()
	$('.modal').modal('show')
	$('.modal-title').text('Agendar Aula')
	$('.modal-body').html(form)
	$('.hora').val($(this).data('hora'))
});

$(".pegarData").on("click", function(e) {

});


			</script>
</x-layoutsadmin>