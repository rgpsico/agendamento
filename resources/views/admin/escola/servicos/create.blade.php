<x-admin.layout title="Criar Alunos">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">{{$pageTitle}}</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item">
                                        <a href="">Admin</a></li>
									<li class="breadcrumb-item active">{{$pageTitle}}</li>
								</ul>
							</div>
						</div>
					</div>

                    <div class="row">
						
						<div class="col-12">
							
							<!-- General -->
							
								<div class="card">
									<div class="card-header">
										<h4 class="card-title">General</h4>
									</div>
									<div class="card-body">
										<div class="card-body">
                                            <x-alert/>
                                            <form action="{{route('admin.servico.store')}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" class="" name="empresa_id" value="{{Auth::user()->id}}" />                                                              
                                              
                                                <x-text-input name="titulo" size="30" label="Titulo" :value="$model ?? ''"/>
                        
                                                <x-text-area name="descricao" label="Descrição" :model="$model ?? ''" />                                                                                       
                                                
                                                <x-text-input name="preco" size="30" label="Preço" :value="$model ?? '' ?? ''" />
                        
                                                <x-text-input name="tempo_de_aula" size="30" label="Tempo de Aula" :value="$model ?? '' ?? ''" />
                                                
                                            
                                            </div>
                                                               
                                                <div class="card-footer d-flex">
                                            <button class="btn btn-success justify-content-right" >Salvar</button>
                                        </div>
									</div>
								</div>
							
							<!-- /General -->
								
						</div>
					</div>
        
            <!-- Page Header -->
        
            <!-- /Page Header -->
            
           
            
        </div>			
    </div>
    <!-- /Page Wrapper -->
</x-layoutsadmin>