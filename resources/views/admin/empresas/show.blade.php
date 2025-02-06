<x-admin.layout title="Aluno">
    <div class="page-wrapper">
        <div class="content container-fluid">
					
           <x-header.titulo pageTitle="TETE"/>
           @include('admin.empresas._partials.modal') 
            
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
                                <h4 class="user-name mb-0">{{$model->name ?? 'Escola de surf'}}</h4>
                                <h6 class="text-muted">{{$model->email ?? 'Escola de surf'}}</h6>
                                <div class="user-Location">
                                    <i class="fa fa-map-marker"></i> {{$model->endereco->cidade ?? 'Rio de Janeiro'}},
                                     {{$model->endereco->nacionalidade ?? 'Brasil'}}</div>
                                <div class="about-text">
                                    {{$model->descricao ?? ''}}
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
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#historicoPg">Historico de Pagamento</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#todosAlunos">Todos os Alunos</a>
                            </li>
                        </ul>
                    </div>	
                    <div class="tab-content profile-tab-cont">
                        
                        <x-empresa.sobrecomponent/>
                        <x-empresa.senhacomponent/>
                        <x-empresa.historicopg/>
                        <x-empresa.alunoscomponent/>
                        <!-- /Change Password Tab -->
                        
                    </div>
                </div>
            </div>
        
        </div>
    </div>
    <!-- /Page Wrapper -->
</x-layoutsadmin>