@extends('alunoadmin::layouts.master')

@section('content')
<div class="page-wrapper" style="min-height: 239px;">
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <x-breadcrumb-aluno title="{{$title}}"/>
        
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
                            <h4 class="user-name mb-0">{{$model->usuario->nome ?? ''}}</h4>
                            <h6 class="text-muted">{{$model->usuario->email ?? ''}}</h6>
                            <div class="user-Location"><i class="fa fa-map-marker"></i> {{$model->endereco->uf ?? ''}}, {{$model->endereco->cidade ?? ''}}</div>
                            <div class="about-text">{{$model->usuario->descricao ?? ''}}</div>
                        </div>
                        <div class="col-auto profile-btn">
                            
                            <a href="" class="btn btn-primary">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
                <div class="profile-menu">
                    <ul class="nav nav-tabs nav-tabs-solid">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#foto">Foto</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link " data-bs-toggle="tab" href="#per_details_tab">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  data-bs-toggle="tab" href="#endereco_tab">Endereço</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  data-bs-toggle="tab" href="#password_tab">Mudar Senha</a>
                        </li>
                    </ul>
                </div>	
                <div class="tab-content profile-tab-cont">
                    
                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade active show" id="per_details_tab">
                    
                        <!-- Personal Details -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between">
                                            <span>Detalhes Pessoais</span> 
                                            <a class="edit-link" data-bs-toggle="modal" href="#edit_personal_details">
                                                <i class="fa fa-edit me-1"></i>Editar</a>
                                        </h5>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Nome</p>
                                            <p class="col-sm-10">{{ $model->usuario->nome }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Nascimento</p>
                                            <p class="col-sm-10">{{ $model->usuario->data_nascimento   ?? ''}}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Email</p>
                                            <p class="col-sm-10">{{ $model->usuario->email ?? '' }}</p>
                                        </div>
                                        {{-- <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Celular</p>
                                            <p class="col-sm-10">{{ $model->usuario->telefone ?? '' }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0">Endereço</p>
                                            <p class="col-sm-10 mb-0">{{ $model->endereco->endereco ?? ''}},<br>
                                            {{ $model->endereco->cidade ?? '' }},<br>
                                            {{ $model->endereco->estado ?? ''}} - {{ $model->cep  ?? ''}},<br>
                                            {{ $model->endereco->pais ?? ''}}.</p>
                                        </div> --}}
                                    </div>
                                    
                                </div>
                                
                                <!-- Edit Details Modal -->
                                @include('alunoadmin::alunos._partials.modal')
                                <!-- /Edit Details Modal -->
                                
                            </div>

                        
                        </div>
                        <!-- /Personal Details -->

                    </div>
                    <!-- /Personal Details Tab -->
                    
                    <!-- Change Password Tab -->
                    <div id="password_tab" class="tab-pane fade">
                    
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Alterar Senha</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form>
                                            <div class="form-group">
                                                <label>Senha Antiga</label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Nova Senha</label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Confirmar Senha</label>
                                                <input type="password" class="form-control">
                                            </div>
                                            <button class="btn btn-primary" type="submit">Salvar Alterações</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- /Change Password Tab -->


                    <div id="endereco_tab" class="tab-pane fade">
                        
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Alterar Senha</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form id="update-endereco-form">
                                            <div class="form-group">
                                                <label>CEP</label>
                                                <input type="text" id="cep" class="form-control">
                                                <span class="text-danger error-text cep_error"></span>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Endereço</label>
                                                <input type="text" id="endereco" class="form-control">
                                                <span class="text-danger error-text endereco_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Cidade</label>
                                                <input type="text" id="cidade" class="form-control">
                                                <span class="text-danger error-text cidade_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Estado</label>
                                                <input type="text" id="estado" class="form-control">
                                                <span class="text-danger error-text estado_error"></span>
                                            </div>
                                          
                                            <button class="btn btn-primary" type="submit">Salvar Alterações</button>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>


                    <div id="foto" class="tab-pane fade">
                        
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Alterar Senha</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                     
                                            <form action="{{route('aluno.upload')}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="usuario_id" value="{{Auth::user()->aluno->id}}">
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                        <input type="file" class="form-control" name="image[]" multiple required>        </div>
                                                    <div class="col-sm-2">
                                                        <button type="submit" class="btn btn-success">Enviar</button>
                                                    </div>
                                                </div>                        
                                            </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    
                </div>
            </div>
        </div>  

      
@endsection
