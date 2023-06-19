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