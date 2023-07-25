<x-admin.layout title="Agendar Aluno ">
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
											@if(isset($model))
											<form action="{{route('empresa.agenda.update',['id' =>$model->id])}}" method="POST" enctype="multipart/form-data">
                                         
											@else 
											<form action="{{route('empresa.agenda.store')}}" method="POST" enctype="multipart/form-data">
                                            @endif
                                            @csrf
                                            
                                                @include('admin.escola.agenda._partials.form')
											
											@if(isset($model))
                                                <div class="card-footer d-flex">
                                            <button class="btn btn-success justify-content-right" >Atualizar</button>
											@else 
											<div class="card-footer d-flex">
												<button class="btn btn-success justify-content-right" >Salvar</button>
											@endif
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