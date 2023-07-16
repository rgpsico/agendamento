<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="Schols "/>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">
						
							<!-- Search Filter -->
							<div class="card search-filter">
								<div class="card-header">
									<h4 class="card-title mb-0">Filtro</h4>
								</div>
								<div class="card-body">
									<div class="filter-widget">
										<div class="input-icon">
											<i class="fas fa-building"></i> <!-- Ícone de empresa do Font Awesome -->
											<input 
											type="text" 
											class="form-control" 
											id="nome_empresa" 
											placeholder="Buscar Escola">
										</div>			
									</div>
								<div class="filter-widget">
							
									<h4>Modalidade</h4>      
									
								
										@foreach ($modalidade as $value )          
											<div>
												<label class="custom_check">
													<input type="checkbox" name="gender_type" data-type="{{$value->nome}} " class="filter_empresa">
													<span class="checkmark"></span> {{$value->nome}} 
												</label>
											</div>
										@endforeach
								
								
							
								</div>
							   
									<div class="btn-search">
										<button type="button" class="btn w-100 buscar_empresa" id="buscar_empresa">Buscar</button>
									</div>	
								</div>
							</div>
							<!-- /Search Filter -->
								<!-- Search Filter -->
						</div>
						
						<div class="col-md-12 col-lg-8 col-xl-9 listar_empresas">

							<!-- Doctor Widget -->
				
							
								
							@foreach ($model as $value )							
								<x-home.cardprofissional :value="$value"/>
                            @endforeach
							<!-- /Doctor Widget -->

							


							<div class="load-more text-center">
								<a class="btn btn-primary btn-sm prime-btn" href="javascript:void(0);">Carregar Mais</a>	
							</div>	
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->
</x-layoutsadmin>

