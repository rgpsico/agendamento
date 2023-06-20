<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="TESTE"/>
			<!-- /Breadcrumb -->


			@include('admin.empresas._partials.modal')
			<div class="container">
				<div class="row">
					<div class="col-12">
					
						<div class="card">
							<div class="card-body">
								<div class="booking-doc-info">
									<a href="doctor-profile.html" class="booking-doc-img">
										<img src="assets/img/doctors/doctor-thumb-02.jpg" alt="User Image">
									</a>
									<div class="booking-info">
										<h4><a href="doctor-profile.html">Dr. Darren Elder</a></h4>
										<div class="rating">
											<i class="fas fa-star filled"></i>
											<i class="fas fa-star filled"></i>
											<i class="fas fa-star filled"></i>
											<i class="fas fa-star filled"></i>
											<i class="fas fa-star"></i>
											<span class="d-inline-block average-rating">35</span>
										</div>
										<p class="text-muted mb-0"><i class="fas fa-map-marker-alt"></i> Newyork, USA</p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-4 col-md-6">
								<h4 class="mb-1">11 November 2019</h4>
								<p class="text-muted">Monday</p>
							</div>
							<div class="col-12 col-sm-8 col-md-6 text-sm-end">
								<div class="bookingrange btn btn-white btn-sm mb-3">
									<i class="far fa-calendar-alt me-2"></i>
									<span>June 12, 2023 - June 18, 2023</span>
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
													<span>Mon</span>
													<span class="slot-date">11 Nov <small class="slot-year">2019</small></span>
												</li>
												<li>
													<span>Tue</span>
													<span class="slot-date">12 Nov <small class="slot-year">2019</small></span>
												</li>
												<li>
													<span>Wed</span>
													<span class="slot-date">13 Nov <small class="slot-year">2019</small></span>
												</li>
												<li>
													<span>Thu</span>
													<span class="slot-date">14 Nov <small class="slot-year">2019</small></span>
												</li>
												<li>
													<span>Fri</span>
													<span class="slot-date">15 Nov <small class="slot-year">2019</small></span>
												</li>
												<li>
													<span>Sat</span>
													<span class="slot-date">16 Nov <small class="slot-year">2019</small></span>
												</li>
												<li>
													<span>Sun</span>
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
							@if(Auth::check())
								<!-- Se o usuário estiver autenticado, redireciona para a rota desejada -->
								<a href="{{ route('home.checkoutAuth',['id' =>1]) }}" class="btn btn-primary submit-btn">Agendar e Pagar</a>
							@else
								<!-- Se o usuário não estiver autenticado, redireciona para a rota de checkout -->
								<a href="{{ route('home.checkout', ['id' =>1]) }}" class="btn btn-primary submit-btn">Agendar e Pagar</a>
							@endif
						</div>
						<!-- /Submit Section -->
						
					</div>
				</div>
					
					<!-- /Submit Section -->
					
				</div>
			</div>
		</div>
			<!-- /Page Content -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

				<script>
					var currentStartIndex = 0;
const dates = getNext29Days();

function getNext29Days() {
  const dates = [];
  for (let i = 0; i < 29; i++) {
    const date = new Date();
    date.setDate(date.getDate() + i);
    dates.push({
      day: date.toLocaleString('default', { weekday: 'short' }),
      date: date.getDate(),
      month: date.toLocaleString('default', { month: 'short' }),
      year: date.getFullYear()
    });
  }
  return dates;
}

function renderDates() {
  const $daySlot = $('.day-slot ul');
  $daySlot.empty();
  $daySlot.append('<li class="left-arrow"><a href=""><i class="fa fa-chevron-left"></i></a></li>');
  
  for (let i = currentStartIndex; i < currentStartIndex + 7; i++) {
    const dateObj = dates[i];
    const $li = $(`
      <li>
        <span>${dateObj.day}</span>
        <span class="slot-date">${dateObj.date} ${dateObj.month} <small class="slot-year">${dateObj.year}</small></span>
      </li>
    `);
    $daySlot.append($li);
  }

  $daySlot.append('<li class="right-arrow"><a href=""><i class="fa fa-chevron-right"></i></a></li>');
}

$(document).ready(function() {
  // Render inicial das datas
  renderDates();

  // Manipuladores de eventos para as setas
  $(document).on('click', '.day-slot .left-arrow', function(e) {
    e.preventDefault();
    if (currentStartIndex > 0) {
      currentStartIndex--;
      renderDates();
    }
  });

  $(document).on('click', '.day-slot .right-arrow', function(e) {
    e.preventDefault();
    if (currentStartIndex < dates.length - 7) {
      currentStartIndex++;
      renderDates();
    }
  });
});

$(document).on('click', '.day-slot li', function(e) {
  e.preventDefault();
  
  // Verifique se o item clicado não é uma seta
  if (!$(this).hasClass('left-arrow') && !$(this).hasClass('right-arrow')) {
    const dayOfWeek = $(this).find('span:first').text();
    const date = $(this).find('.slot-date').text();
    console.log('Dia da semana:', dayOfWeek);
    console.log('Data:', date);
  }
});


				</script>
</x-layoutsadmin>