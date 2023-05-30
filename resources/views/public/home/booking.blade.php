<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="TESTE"/>
			<!-- /Breadcrumb -->
			<div class="container">
			<div class="row">
				<div class="col-12">
				
					<div class="card">
						<div class="card-body">
							<div class="booking-doc-info">
								<a href="doctor-profile.html" class="booking-doc-img">
									<img src="{{asset('template/assets/img/doctors/doctor-thumb-02.jpg')}}" alt="User Image">
								</a>
								<div class="booking-info">
									<h4><a href="doctor-profile.html">{{$model->nome}}</a></h4>
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
											<li>
												<span>Segunda</span>
												<span class="slot-date">11 Nov 
													<small class="slot-year">2019</small>
												</span>
											</li>
											<li>
												<span>Terça</span>
												<span class="slot-date">12 Nov 
													<small class="slot-year">2019</small>
												</span>
											</li>
											<li>
												<span>Quarta</span>
												<span class="slot-date">13 Nov 
													<small class="slot-year">2019</small>
												</span>
											</li>
											<li>
												<span>Quinta</span>
												<span class="slot-date">14 Nov 
													<small class="slot-year">2019</small>
												</span>
											</li>
											<li>
												<span>Sexta</span>
												<span class="slot-date">15 Nov 
													<small class="slot-year">2019</small>
												</span>
											</li>
											<li>
												<span>Sabado</span>
												<span class="slot-date">16 Nov <small class="slot-year">2019</small></span>
											</li>
											<li>
												<span>Domingo</span>
												<span class="slot-date">17 Nov <small class="slot-year">2019</small></span>
											</li>
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
											<li>
												<a class="timing" href="#">
													<span>9:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>10:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>11:00</span> <span>AM</span>
												</a>
											</li>
											<li>
												<a class="timing" href="#">
													<span>9:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>10:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>11:00</span> <span>AM</span>
												</a>
											</li>
											<li>
												<a class="timing" href="#">
													<span>9:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>10:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>11:00</span> <span>AM</span>
												</a>
											</li>
											<li>
												<a class="timing" href="#">
													<span>9:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>10:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>11:00</span> <span>AM</span>
												</a>
											</li>
											<li>
												<a class="timing" href="#">
													<span>9:00</span> <span>AM</span>
												</a>
												<a class="timing selected" href="#">
													<span>10:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>11:00</span> <span>AM</span>
												</a>
											</li>
											<li>
												<a class="timing" href="#">
													<span>9:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>10:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>11:00</span> <span>AM</span>
												</a>
											</li>
											<li>
												<a class="timing" href="#">
													<span>9:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>10:00</span> <span>AM</span>
												</a>
												<a class="timing" href="#">
													<span>11:00</span> <span>AM</span>
												</a>
											</li>
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
						<a href="{{route('home.checkout',['id' =>$model->uuid])}}" 
							class="btn btn-primary submit-btn">Agendar e Pagar</a>
					</div>
					<!-- /Submit Section -->
					
				</div>
			</div>
		</div>
			<!-- /Page Content -->
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
    for (let i = 0; i < 7; i++) {
        let date = new Date(currentDate);
        date.setDate(currentDate.getDate() + i);
        calendarDays[i].textContent = date.toLocaleDateString('pt-BR');
        dayNames[i].textContent = daysOfWeek[date.getDay()];
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

// Atualizar o calendário pela primeira vez.
updateCalendar();

			</script>
</x-layoutsadmin>