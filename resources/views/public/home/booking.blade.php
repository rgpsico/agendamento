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
					<div class="row">
						<div class="col-12 col-sm-4 col-md-6">
							<h4 class="mb-1">11 November 2019</h4>
							<p class="text-muted">Monday</p>
						</div>
						<div class="col-12 col-sm-8 col-md-6 text-sm-end">
							<div class="bookingrange btn btn-white btn-sm mb-3">
								<i class="far fa-calendar-alt me-2"></i>
								<span>May 15, 2023 - May 21, 2023</span>
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
</x-layoutsadmin>