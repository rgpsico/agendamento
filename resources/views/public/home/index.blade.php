<x-public.layout title="HOME">
   
    <!-- Breadcrumb -->
		<x-home.breadcrumb title="Scholssssss "/>
			<!-- /Breadcrumb -->
			
			<!-- Page Content -->
			<div class="content">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-lg-4 col-xl-3 theiaStickySidebar">
						
							<!-- Search Filter -->
							@include('public.home._partials.filter')
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

