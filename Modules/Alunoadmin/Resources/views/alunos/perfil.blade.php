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
                            <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Aulas</a>
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
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Data de Nascimento</p>
                                            <p class="col-sm-10">{{ $model->data_de_nascimento  ?? ''}}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Email</p>
                                            <p class="col-sm-10">{{ $model->usuario->email ?? '' }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Celular</p>
                                            <p class="col-sm-10">{{ $model->endereco->celular ?? '' }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0">Endereço</p>
                                            <p class="col-sm-10 mb-0">{{ $model->endereco->endereco ?? ''}},<br>
                                            {{ $model->endereco->cidade ?? '' }},<br>
                                            {{ $model->endereco->estado ?? ''}} - {{ $model->cep  ?? ''}},<br>
                                            {{ $model->endereco->pais ?? ''}}.</p>
                                        </div>
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
                    
                </div>
            </div>
        </div>  

        <script src="{{asset('admin/js/jquery-3.6.4.min.js')}}"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="{{asset('admin/js/bootstrap.bundle.min.js')}}"></script>
		
		<!-- Slimscroll JS -->
        <script src="{{asset('admin/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

        <!-- Bootstrap Datetimepicker JS -->
        <script  src="{{asset('admin/js/moment.min.js')}}"></script>
		<script  src="{{asset('admin/js/bootstrap-datetimepicker.min.js')}}"></script>
		
		<!-- Custom JS -->
		<script  src="{{asset('admin/js/script.js')}}"></script>
@endsection
