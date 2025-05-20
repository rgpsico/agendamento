@extends('alunoadmin::layouts.master')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-image {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .profile-image img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .profile-image img:hover {
        transform: scale(1.05);
    }

    .profile-user-info {
        padding: 10px 0;
        text-align: left;
    }

    .profile-user-info h4 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
    }

    .profile-user-info h6 {
        font-size: 1rem;
        color: #6c757d;
    }

    .user-location {
        font-size: 0.9rem;
        color: #555;
    }

    .about-text {
        font-size: 0.95rem;
        color: #444;
        margin-top: 10px;
    }

    .profile-btn .btn {
        border-radius: 20px;
        padding: 8px 20px;
        font-size: 0.9rem;
    }

    .profile-menu .nav-tabs {
        border-bottom: 2px solid #e9ecef;
    }

    .profile-menu .nav-link {
        color: #495057;
        font-weight: 500;
        padding: 10px 20px;
        transition: all 0.3s;
    }

    .profile-menu .nav-link.active {
        background-color: #007bff;
        color: #fff;
        border-radius: 5px 5px 0 0;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
    }

    .edit-link {
        color: #007bff;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .edit-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .pic-holder {
        text-align: center;
        position: relative;
        border-radius: 50%;
        width: 150px;
        height: 150px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 20px;
        border: 2px solid #e9ecef;
    }

    .pic-holder .pic {
        height: 100%;
        width: 100%;
        object-fit: cover;
        object-position: center;
    }

    .pic-holder .upload-file-block {
        cursor: pointer;
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: all 0.2s;
    }

    .pic-holder .upload-file-block:hover {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 15px;
        }

        .profile-image img {
            width: 100px;
            height: 100px;
        }

        .profile-user-info h4 {
            font-size: 1.25rem;
        }

        .profile-user-info h6 {
            font-size: 0.9rem;
        }

        .profile-btn .btn {
            padding: 6px 15px;
            font-size: 0.85rem;
        }

        .profile-menu .nav-link {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="page-wrapper" style="min-height: 239px;">
    <div class="content container-fluid">
        <!-- Page Header -->
        <x-breadcrumb-aluno title="{{ $title }}" />

        <div class="row">
            <div class="col-md-12">
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-auto profile-image">
                            <a href="#">
                                <img class="rounded-circle" alt="Profile Image"
                                     src="{{ $model->avatar ? asset('storage/' . $model->avatar) : asset('assets/img/user-default.png') }}">
                            </a>
                        </div>
                        <div class="col ml-md-n2 profile-user-info">
                            <h4 class="user-name mb-0">{{ $model->usuario->nome ?? '' }}</h4>
                            <h6 class="text-muted">{{ $model->usuario->email ?? '' }}</h6>
                            <div class="user-location">
                                <i class="fa fa-map-marker"></i> {{ $model->endereco->uf ?? '' }}, {{ $model->endereco->cidade ?? '' }}
                            </div>
                            <div class="about-text">{{ $model->usuario->descricao ?? '' }}</div>
                        </div>
                        <div class="col-auto profile-btn">
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_personal_details">
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="profile-menu">
                    <ul class="nav nav-tabs nav-tabs-solid">
                        {{-- <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#foto">Foto</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#per_details_tab">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#endereco_tab">Endereço</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Mudar Senha</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content profile-tab-cont">
                    <!-- Foto Tab -->
                    <div class="tab-pane fade show active" id="foto">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Atualizar Foto de Perfil</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form id="form-update-foto" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="aluno_id" value="{{ Auth::user()->id }}">
                                            <div class="form-group">
                                                <div class="pic-holder">
                                                    <img id="profilePic" class="pic"
                                                         src="{{ $model->avatar ? asset('storage/' . $model->avatar) : asset('assets/img/user-default.png') }}">
                                                    <label for="newProfilePhoto" class="upload-file-block">
                                                        <div class="text-center">
                                                            <div class="mb-2">
                                                                <i class="fa fa-camera fa-2x"></i>
                                                            </div>
                                                            <div class="text-uppercase">
                                                                Atualizar Foto
                                                            </div>
                                                        </label>
                                                        <input type="file" name="profile_image" id="newProfilePhoto" accept="image/*" style="display: none;">
                                                    </div>
                                                </div>
                                                <span class="text-danger error-text profile_image_error"></span>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Salvar Foto</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade" id="per_details_tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between">
                                            <span>Detalhes Pessoais</span>
                                            <a class="edit-link" data-bs-toggle="modal" href="#edit_personal_details">
                                                <i class="fa fa-edit me-1"></i>Editar
                                            </a>
                                        </h5>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Nome</p>
                                            <p class="col-sm-10">{{ $model->usuario->nome ?? '' }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Nascimento</p>
                                            <p class="col-sm-10">{{ $model->usuario->data_nascimento ? date('d/m/Y', strtotime($model->usuario->data_nascimento)) : '' }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Email</p>
                                            <p class="col-sm-10">{{ $model->usuario->email ?? '' }}</p>
                                        </div>
                                        <div class="row">
                                            <p class="col-sm-2 text-muted text-sm-end mb-0 mb-sm-3">Celular</p>
                                            <p class="col-sm-10">{{ $model->usuario->telefone ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Tab -->
                    <div class="tab-pane fade" id="endereco_tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Atualizar Endereço</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form id="update-endereco-form">
                                            @csrf
                                            <input type="hidden" name="aluno_id" value="{{ Auth::user()->id }}">
                                            <div class="form-group">
                                                <label>CEP</label>
                                                <input type="text" id="cep" name="cep" class="form-control" value="{{ $model->endereco->cep ?? '' }}">
                                                <span class="text-danger error-text cep_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Endereço</label>
                                                <input type="text" id="endereco" name="endereco" class="form-control" value="{{ $model->endereco->endereco ?? '' }}">
                                                <span class="text-danger error-text endereco_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Cidade</label>
                                                <input type="text" id="cidade" name="cidade" class="form-control" value="{{ $model->endereco->cidade ?? '' }}">
                                                <span class="text-danger error-text cidade_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Estado</label>
                                                <input type="text" id="estado" name="estado" class="form-control" value="{{ $model->endereco->uf ?? '' }}">
                                                <span class="text-danger error-text estado_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>País</label>
                                                <input type="text" id="pais" name="pais" class="form-control" value="{{ $model->endereco->pais ?? '' }}">
                                                <span class="text-danger error-text pais_error"></span>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Salvar Alterações</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Tab -->
                    <div class="tab-pane fade" id="password_tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Alterar Senha</h5>
                                <div class="row">
                                    <div class="col-md-10 col-lg-6">
                                        <form id="update-password-form">
                                            @csrf
                                            <div class="form-group">
                                                <label>Senha Antiga</label>
                                                <input type="password" name="old_password" class="form-control">
                                                <span class="text-danger error-text old_password_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Nova Senha</label>
                                                <input type="password" name="new_password" class="form-control">
                                                <span class="text-danger error-text new_password_error"></span>
                                            </div>
                                            <div class="form-group">
                                                <label>Confirmar Senha</label>
                                                <input type="password" name="new_password_confirmation" class="form-control">
                                                <span class="text-danger error-text new_password_confirmation_error"></span>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Salvar Alterações</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include the Edit Personal Details Modal -->
@include('alunoadmin::alunos._partials.modal')

<script>
$(document).ready(function() {
    // Preview da imagem no Foto tab
    $("#newProfilePhoto").change(function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#profilePic").attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Submissão do formulário de foto
    $('#form-update-foto').on('submit', function(e) {
        e.preventDefault();
        var alunoId = $('input[name="aluno_id"]').val();
        var formData = new FormData(this);

        $.ajax({
            url: '/api/aluno/' + alunoId + '/update',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message);
                if (response.message === 'Dados atualizados com sucesso!') {
                    window.location.reload();
                }
            },
            error: function(response) {
                if (response.status === 422) {
                    $('.text-danger.error-text').text('');
                    const errors = response.responseJSON.errors;
                    for (let field in errors) {
                        $(`.${field}_error`).text(errors[field][0]);
                    }
                } else if (response.status === 404) {
                    alert(response.responseJSON.message || 'Aluno ou usuário não encontrado!');
                } else {
                    alert('Ocorreu um erro desconhecido!');
                }
            }
        });
    });

    // Submissão do formulário de endereço (placeholder - backend needed)
    $('#update-endereco-form').on('submit', function(e) {
        e.preventDefault();
        var alunoId = $('input[name="aluno_id"]').val();
        var formData = {
            cep: $('#cep').val(),
            endereco: $('#endereco').val(),
            cidade: $('#cidade').val(),
            estado: $('#estado').val(),
            pais: $('#pais').val(),
        };

        $.ajax({
            url: '/api/aluno/' + alunoId + '/update-endereco', // Placeholder endpoint
            method: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message || 'Endereço atualizado com sucesso!');
                window.location.reload();
            },
            error: function(response) {
                if (response.status === 422) {
                    $('.text-danger.error-text').text('');
                    const errors = response.responseJSON.errors;
                    for (let field in errors) {
                        $(`.${field}_error`).text(errors[field][0]);
                    }
                } else {
                    alert('Ocorreu um erro ao atualizar o endereço!');
                }
            }
        });
    });

    // Submissão do formulário de senha (placeholder - backend needed)
    $('#update-password-form').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            old_password: $('input[name="old_password"]').val(),
            new_password: $('input[name="new_password"]').val(),
            new_password_confirmation: $('input[name="new_password_confirmation"]').val(),
        };

        $.ajax({
            url: '/api/aluno/update-password', // Placeholder endpoint
            method: 'POST',
            data: formData,
            success: function(response) {
                alert(response.message || 'Senha atualizada com sucesso!');
                window.location.reload();
            },
            error: function(response) {
                if (response.status === 422) {
                    $('.text-danger.error-text').text('');
                    const errors = response.responseJSON.errors;
                    for (let field in errors) {
                        $(`.${field}_error`).text(errors[field][0]);
                    }
                } else {
                    alert('Ocorreu um erro ao atualizar a senha!');
                }
            }
        });
    });
});
</script>
@endsection