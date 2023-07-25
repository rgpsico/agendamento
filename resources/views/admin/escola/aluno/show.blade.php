<x-admin.layout title="Aluno">
    <div class="page-wrapper">
        <div class="content container-fluid">
					
           <x-header.titulo pageTitle="Alunos"/>
        
            
           
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-auto profile-image">
                                <a href="#">
                                    <img class="rounded-circle" alt="User Image" src="{{asset('admin/img/profiles/avatar-01.jpg')}}">
                                </a>
                            </div>
                            <div class="col ml-md-n2 profile-user-info">
                                <h4 class="user-name mb-0">{{$model->nome ?? ''}}</h4>
                                <h6 class="text-muted">{{$model->email ?? ''}}</h6>
                                <div class="user-Location">
                                    <i class="fa fa-map-marker"></i> {{$model->endereco ?? ''}}, {{$model->endereco->pais ?? ''}}</div>
                                <div class="about-text">
                                    {{$model->description ?? ''}}
                                </div>
                            </div>
                            <div class="col-auto profile-btn">                                
                                <a href="" class="btn btn-primary">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                   
                    <div class="profile-menu">
                        <ul class="nav nav-tabs nav-tabs-solid">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">Sobre</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Senha</a>
                            </li>
                        </ul>
                    </div>	

                    <div class="tab-content profile-tab-cont">
                        
                        <!-- Personal Details Tab -->
                        
                        <!-- /Personal Details Tab -->
                        
                        <!-- Change Password Tab -->
                        <div id="password_tab" class="tab-pane fade">
                        
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Mudar Senha</h5>
                                    <div class="row">
                                        <div class="col-md-10 col-lg-6">
                                            <form>
                                                <div class="form-group">
                                                    <label>Senha Antiga</label>
                                                    <input type="password" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Nova senha</label>
                                                    <input type="password" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Confirmar Senha</label>
                                                    <input type="password" class="form-control">
                                                </div>
                                                <button class="btn btn-primary" type="submit">Salvar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Change Password Tab -->
                        
                    </div>
                </div>
            </div>
        
        </div>
    </div>
    <!-- /Page Wrapper -->
</x-layoutsadmin>