<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="{{$model->nome}}"  :model="$model" />
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container">

					<!-- Doctor Widget -->
					<div class="card">
						<div class="card-body">
							<div class="doctor-widget">
								<div class="doc-info-left">
									<div class="doctor-img">
										<img src="{{ asset('avatar/' . $model->avatar) }}" class="img-fluid" alt="Usuario Image">
										</div>
								
									<div class="doc-info-cont">
										<h4 class="doc-name">{{$model->nome}}</h4>
										<p class="doc-speciality">{{$model->descricao}}</p>
										<p class="doc-department">
											<img src="{{ asset('avatar/' . $model->avatar) }}" 
											class="img-fluid" 
											alt="Usuario Image">
											<span class="badge bg-primary" style="font-size: 1.2em; text-transform: capitalize;">{{$model->modalidade}}</span>
										</p>


										<x-avaliacao-home :model="$model" />


										<div class="clinic-details">
											<p class="doc-location">
												<i class="fas fa-map-marker-alt"></i> {{$model->endereco->endereco ?? 'BR'}}, {{$model->endereco->uf ?? 'RJ'}} - 
												<a href="javascript:void(0);">Ver localização</a>
											</p>

											<ul class="clinic-gallery">
												@foreach ($model->galeria as $gal )													
												<li>
													<a href="{{ asset('galeria_escola/' . $gal->image) }}" data-fancybox="gallery">
														<img src="{{ asset('galeria_escola/' . $gal->image) }}" alt="Feature" class="img-fluid">
													</a>
												</li>
												@endforeach
												
											</ul>
										</div>
										<div class="clinic-services">
											<span>{{$model->modalidade}}</span>
											
										</div>
									</div>
								</div>
								<div class="doc-info-right">
									<div class="clini-infos">
										<ul>
											<li><i class="far fa-thumbs-up"></i> {{ round(($model->avaliacao->avg('avaliacao') / 5) * 100) }}%</li>

											<li><i class="far fa-comment"></i> {{$model->avaliacao->count() ?? ''}} Feedback</li>
											<li><i class="fas fa-map-marker-alt"></i> {{$model->endereco->cidade ?? ''}}, {{$model->endereco->pais ?? ''}}</li>
											<li><i class="far fa-money-bill-alt"></i> {{$model->valor_aula_de ?? ''}} Até {{$model->valor_aula_ate ?? ''}}</li>
										</ul>
									</div>
									<div class="doctor-action">
										<a href="https://wa.me/{{$model->telefone}}" target="_blank" class="btn btn-white call-btn">
																			<i class="far fa-comment-alt"></i>
										</a>
										
										<a href="tel:+5521990271287" class="btn btn-white call-btn">
											<i class="fas fa-phone"></i>
										</a>
										
										
								   </div>
									<div class="clinic-booking">
										<a class="apt-btn" href="{{route('home.booking',['id' => $model->user_id])}}">Agendar</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /Doctor Widget -->
					
					<!-- Doctor Details Tab -->
					<div class="card">
						<div class="card-body pt-0">
						
							<!-- Tab Menu -->
							<nav class="user-tabs mb-4">
								<ul class="nav nav-tabs nav-tabs-bottom nav-justified" role="tablist">
									<li class="nav-item" role="presentation">
										<a class="nav-link active" href="#doc_overview" data-bs-toggle="tab" aria-selected="true" role="tab">Sobre nós</a>
									</li>
									<li class="nav-item" role="presentation">
										<a class="nav-link" href="#doc_locations" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Localização</a>
									</li>
									<li class="nav-item" role="presentation">
										<a class="nav-link" href="#doc_business_hours" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Horario de funcionamento</a>
									</li>
									{{-- <li class="nav-item" role="presentation">
										<a class="nav-link" href="#doc_reviews" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Feedbacks</a>
									</li> --}}
									
								</ul>
							</nav>
							<!-- /Tab Menu -->
							
							<style>
								.gallery-image {
								  width: 100%;
								  height: 100px;
								}
							  
								.card {
								  margin-bottom: 15px;
								}
							  </style>
							<!-- Tab Content -->
							<div class="tab-content pt-0">
							
								<!-- Overview Content -->
								<div role="tabpanel" id="doc_overview" class="tab-pane fade show active">
									<div class="row">
										<div class="col-md-6 col-lg-6">
										
											<!-- About Details -->
											<div class="widget about-widget">
												<h4 class="widget-title">Sobre nós</h4>
												<p>{{$model->descricao}}</p>
											</div>											
										</div>
										<div class="col-6">
											<div class="widget about-widget">
											  <h4 class="widget-title">Galeria</h4>
											  <div class="row">
												@foreach ($model->galeria as $gal)
												<div class="col-md-4">
												  <div class="card">
													<img class="card-img-top gallery-image" src="{{ asset('galeria_escola/' . $gal->image) }}" alt="Imagem do serviço">
												  </div>
												</div>
												@endforeach
											  </div>
											</div>
										  </div>
										 
										  
										  
									</div>
								</div>
								<div role="tabpanel" id="doc_locations" class="tab-pane fade">
								
									<!-- Location List -->
									<div class="location-list">
										<div class="row">
										
											<!-- Clinic Content -->
											<div class="col-md-4">
												<div class="clinic-content">
													<h4 class="clinic-name"><a href="#">{{$model->nome}}</a></h4>
													<p class="doc-speciality">{{$model->descricao}}</p>
													
													<x-avaliacao-home :model="$model"/>
													<div class="clinic-details mb-0">
														<h5 class="clinic-direction"> <i class="fas fa-map-marker-alt">
															</i> {{$model->endereco->numero ?? ''}}  {{$model->endereco->bairro ?? ''}}, {{$model->endereco->cidade ?? ''}}, {{$model->endereco->cep ?? ''}} {{$model->endereco->pais ?? ''}} <br>
															<a href="javascript:void(0);">Ver Mapa</a></h5>
														<ul>
															@foreach ($model->galeria as $gal )
																<li>
																	<a href="{{ asset('galeria_escola/' . $gal->image) }}" data-fancybox="gallery2">
																		<img src="{{ asset('galeria_escola/' . $gal->image) }}" alt="Feature Image">
																	</a>
																</li>
															@endforeach
															
														</ul>
													</div>
												</div>
											</div>
											<!-- /Clinic Content -->
											
											<!-- Clinic Timing -->
											<div class="col-md-3">
												<div class="clinic-timing">
													<div>
														<p class="timings-days">
															<span> Seg à Seg </span>
														</p>
														<p class="timings-times">
															<span>07:00 Am 19:00 PM</span>
															
														</p>
													</div>
													<div>
													<p class="timings-days">
														<span>Domingo</span>
													</p>
													<p class="timings-times">
														<span>07:00 AM - 19:00 PM</span>
													</p>
													</div>
												</div>
											</div>
											<!-- /Clinic Timing -->
											
											<div class="col-md-5">
												<div class="consult-price">
												  <iframe
													width="100%"
													height="300"
													frameborder="0"
													style="border:0"
													src="https://www.google.com/maps/embed"
													allowfullscreen
												  ></iframe>
												</div>
											  </div>
											  
										</div>
									</div>
									<!-- /Location List -->
									
									<!-- Location List -->
									<div class="location-list">
										<div class="row">
										
											<!-- Clinic Content -->
											
											<!-- /Clinic Content -->
											
											<!-- Clinic Timing -->
											
											<!-- /Clinic Timing -->
											
										
										</div>
									</div>
									<!-- /Location List -->

								</div>
								<!-- /Locations Content -->
								
								<!-- Reviews Content -->
								{{-- @include('public.home._partials.feedback') --}}
								<!-- /Reviews Content -->
								
								<!-- Business Hours Content -->
								@include('public.home._partials.horariosdefuncionamento')
								<!-- /Business Hours Content -->
								
							</div>
						</div>
					</div>
					<!-- /Doctor Details Tab -->

				</div>

			</div>		
			<!-- /Page Content -->
</x-layoutsadmin>