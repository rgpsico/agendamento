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
                                    <img class="rounded-circle" alt="User Image" src="{{ asset('banner/' . $empresa->banners) }}">
                                </a>
                            </div>
                            <div class="col ml-md-n2 profile-user-info">
                                <h4 class="user-name mb-0">{{$empresa->nome}}</h4>
                                <h6 class="text-muted">{{$empresa->email }}</h6>
                                <div class="user-Location">
                                    <i class="fa fa-map-marker"></i> {{$empresa->endereco->cidade}},
                                     {{$empresa->endereco->nacionalidade}}</div>
                                <div class="about-text">
                                    {{$empresa->descricao}}
                                </div>
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
                        
                        <x-empresa.sobrecomponent  :empresa="$empresa"/>
                        <x-empresa.senhacomponent/>
                        <x-empresa.historicopg/>
                        <x-empresa.alunoscomponent :alunos="$alunos"/>
                        <!-- /Change Password Tab -->
                        
                    </div>
                </div>
            </div>
        
        </div>
    </div>
    <!-- /Page Wrapper -->
</x-layoutsadmin>