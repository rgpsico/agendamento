<x-admin.layout title="{{$pageTitle}}">
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
										<h4 class="card-title">Cadastrar Aluno</h4>
									</div>
									<div class="card-body">
										<div class="card-body">
                                            <x-alert/>
											@if(isset($model->id))
											<form action="{{route('alunos.update', ['id' => $model->id])}}" method="POST" enctype="multipart/form-data">
												@csrf
												@method('post')
											
											@else 
											<form action="{{route('alunos.store')}}" method="POST" enctype="multipart/form-data">
                                            @endif
                                            @csrf
                                            
											@include('admin.escola.aluno._partials.form')  
											
											@if(isset($model->id))
                                                <div class="card-footer d-flex">
                                            <button class="btn btn-success justify-content-right" >Atualizar</button>
											@else 
											<div class="card-footer d-flex">
												<button class="btn btn-success justify-content-right" >Cadastrar</button>
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