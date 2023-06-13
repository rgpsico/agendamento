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
							<div class="booking-doc-info">
								<a href="doctor-profile.html" class="booking-doc-img">
									<img src="{{asset('template/assets/img/doctors/doctor-thumb-02.jpg')}}" alt="User Image">
								</a>
								<div class="booking-info">
									<h4><a href="doctor-profile.html">{{$model->usuarios->nome ?? ''}}</a></h4>
									<div class="rating">
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star"></i>
										<span class="d-inline-block average-rating">35</span>
									</div>
									<p class="text-muted mb-0"><i class="fas fa-map-marker-alt"></i> {{$model->endereco->cidade ?? ''}}, {{$model->endereco->nacionalidade ?? ''}}</p>
								</div>
							</div>
						</div>
					</div>
					@php
    					\Carbon\Carbon::setLocale('pt_BR');
					@endphp

			<div class="row">
				<div class="col-12 col-sm-4 col-md-6">
					<h4 class="mb-1">{{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</h4>
					<p class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd') }}</p>
				</div>
				<div class="col-12 col-sm-8 col-md-6 text-sm-end">
					<div class="bookingrange btn btn-white btn-sm mb-3">
						<i class="far fa-calendar-alt me-2"></i>
						<span>{{ \Carbon\Carbon::now()->isoFormat('MMMM D, YYYY') }} - {{ \Carbon\Carbon::now()->addWeek()->isoFormat('MMMM D, YYYY') }}</span>
						<i class="fas fa-chevron-down ms-2"></i>
					</div>
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
										<ul>
											<li class="left-arrow">
												<a href="">
													<i class="fa fa-chevron-left"></i>
												</a>
											</li>
											@foreach ($aulasDias as $aula)
												<li data-dia="{{$aula->dia}}" id="pegarData">
													<span>{{ $aula->dia_id }}</span>
													<span class="slot-date">11 Nov 
														<small class="slot-year">2019</small>
													</span>
												</li>
											@endforeach
											<li class="right-arrow">
												<a href="">
													<i class="fa fa-chevron-right"></i>
												</a>
											</li>
										</ul>
									</div>
									<!-- /Day Slot -->
									
								</div>
							</div>
						</div>
						<!-- /Schedule Header -->
						
						<!-- Schedule Content -->
						<div class="schedule-cont">
							<div class="row">
								<div class="col-md-12">
								
									<!-- Time Slot -->
									
									
									<div class="time-slot">
										<ul class="clearfix">
											@foreach ($horarios as $dia => $horas)
											<li data-dia="{{$dia}}"   id="">
												@foreach ($horas as $hora)
													<a class="timing agendarAulas" href="#" data-hora="{{ $hora }}">
														<span>{{ $hora }}</span> <span>{{$hora < 12 ? 'AM' : 'PM'}}</span>
													</a>	
												@endforeach
											</li>
											@endforeach
										</ul>
									</div>
								
									<!-- /Time Slot -->
									
								</div>
							</div>
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
			// Primeiro, vamos definir a data inicial como a data atual em Brasília.
let currentDate = new Date();
let offset = currentDate.getTimezoneOffset() + (3 * 60); // Brasília está 3 horas atrás do UTC
currentDate = new Date(currentDate.getTime() + (offset * 60 * 1000));

// Nomes dos dias da semana em português.
let daysOfWeek = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado'];

// Função para atualizar o calendário.
function updateCalendar() {
    let calendarDays = document.querySelectorAll('.slot-date');
    let dayNames = document.querySelectorAll('.day-slot ul li span:first-child');

	try {
		for (let i = 0; i < 7; i++) {
        let date = new Date(currentDate);
        date.setDate(currentDate.getDate() + i);
        calendarDays[i].textContent = date.toLocaleDateString('pt-BR');
        dayNames[i].textContent = daysOfWeek[date.getDay()];
    }
		
	} catch (error) {
		
	}
 
}

// Função para avançar uma semana.
function nextWeek() {
    currentDate.setDate(currentDate.getDate() + 7);
    updateCalendar();
}

// Função para retroceder uma semana.
function previousWeek() {
    currentDate.setDate(currentDate.getDate() - 7);
    updateCalendar();
}

// Adicionar event listeners para os botões de seta.
document.querySelector('.right-arrow').addEventListener('click', function(event) {
    event.preventDefault();
    nextWeek();
});

document.querySelector('.left-arrow').addEventListener('click', function(event) {
    event.preventDefault();
    previousWeek();
});




// Atualizar os slots de aula pela primeira vez.


// Atualizar o calendário pela primeira vez.
updateCalendar();

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
	alert("aaa")
});


			</script>
</x-layoutsadmin>