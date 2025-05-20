@extends('alunoadmin::layouts.master')

@section('content')
<!-- Move styles to a separate CSS file or Blade partial -->
@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    .profile-image img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease;
    }

    .profile-image img:hover {
        transform: scale(1.05);
    }

    .profile-user-info {
        flex: 1;
        padding: 0 1rem;
    }

    .profile-user-info h4 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .profile-user-info h6 {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .user-location {
        font-size: 0.9rem;
        color: #555;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .about-text {
        font-size: 0.95rem;
        color: #444;
        margin-top: 0.5rem;
    }

    .profile-btn .btn {
        border-radius: 20px;
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .profile-menu .nav-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1.5rem;
    }

    .profile-menu .nav-link {
        color: #495057;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s;
        border-radius: 8px 8px 0 0;
    }

    .profile-menu .nav-link.active {
        background-color: #007bff;
        color: #fff;
        border-bottom: none;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }

    .edit-link {
        color: #007bff;
        font-size: 0.9rem;
        cursor: pointer;
        transition: color 0.2s;
    }

    .edit-link:hover {
        color: #0056b3;
        text-decoration: none;
    }

    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-group .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 0.5rem 1rem;
        font-size: 0.95rem;
    }

    .form-group .text-danger {
        font-size: 0.85rem;
        position: absolute;
        bottom: -1.25rem;
        left: 0;
    }

    .pic-holder {
        text-align: center;
        position: relative;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 1rem;
        border: 2px solid #e9ecef;
        background-color: #f8f9fa;
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
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pic-holder .upload-file-block:hover {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .profile-header {
            padding: 1rem;
            flex-direction: column;
            text-align: center;
        }

        .profile-image img {
            width: 80px;
            height: 80px;
        }

        .profile-user-info {
            padding: 0.5rem 0;
        }

        .profile-user-info h4 {
            font-size: 1.25rem;
        }

        .profile-user-info h6 {
            font-size: 0.9rem;
        }

        .profile-btn .btn {
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
        }

        .profile-menu .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .pic-holder {
            width: 100px;
            height: 100px;
        }
    }
</style>
@endpush

<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <x-breadcrumb-aluno title="{{ $title }}" />

        <div class="row">
            <div class="col-md-12">
                <div class="profile-header">
                    <div class="row align-items-center w-100">
                        <div class="col-auto profile-image">
                            <img class="rounded-circle" alt="Profile Image"
                                 src="{{ $model->avatar ? asset('storage/' . $model->avatar) : asset('assets/img/user-default.png') }}"
                                 title="Clique para editar" data-bs-toggle="modal" data-bs-target="#edit_personal_details">
                        </div>
                        <div class="col profile-user-info">
                            <h4 class="user-name mb-0">{{ $model->usuario->nome ?? 'Sem Nome' }}</h4>
                            <h6 class="text-muted">{{ $model->usuario->email ?? 'Sem Email' }}</h6>
                            <div class="user-location">
                                {{-- <i class="fa fa-map-marker"></i>
                                {{ $model->endereco->uf ?? '' }}
                                {{ $model->endereco->uf && $model->endereco->cidade ? ',' : '' }}
                                {{ $model->endereco->cidade ?? '' }} --}}
                            </div>
                            <div class="about-text">{{ $model->usuario->descricao ?? 'Nenhuma descrição disponível' }}</div>
                        </div>
                        <div class="col-auto profile-btn">
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit_personal_details">
                                <i class="fa fa-edit me-1"></i> Editar Perfil
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
                            <a class="nav-link" data-bs-toggle="tab" href="#endereco_tab">Endereço</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Mudar Senha</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content profile-tab-cont">
                    <!-- Personal Details Tab -->
                    <div class="tab-pane fade show active" id="per_details_tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between align-items-center">
                                    <span>Detalhes Pessoais</span>
                                    <a class="edit-link" data-bs-toggle="modal" href="#edit_personal_details">
                                        <i class="fa fa-edit me-1"></i>Editar
                                    </a>
                                </h5>
                                <div class="row mb-3">
                                    <p class="col-sm-3 col-md-2 text-muted text-sm-end mb-0 mb-sm-2">Nome</p>
                                    <p class="col-sm-9 col-md-10">{{ $model->usuario->nome ?? 'Sem Nome' }}</p>
                                </div>
                                <div class="row mb-3">
                                    <p class="col-sm-3 col-md-2 text-muted text-sm-end mb-0 mb-sm-2">Nascimento</p>
                                    <p class="col-sm-9 col-md-10">{{ $model->usuario->data_nascimento ? date('d/m/Y', strtotime($model->usuario->data_nascimento)) : 'Não informado' }}</p>
                                </div>
                                <div class="row mb-3">
                                    <p class="col-sm-3 col-md-2 text-muted text-sm-end mb-0 mb-sm-2">Email</p>
                                    <p class="col-sm-9 col-md-10">{{ $model->usuario->email ?? 'Sem Email' }}</p>
                                </div>
                                <div class="row mb-3">
                                    <p class="col-sm-3 col-md-2 text-muted text-sm-end mb-0 mb-sm-2">Celular</p>
                                    <p class="col-sm-9 col-md-10">{{ $model->usuario->telefone ?? 'Não informado' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Tab -->
                    <div class="tab-pane fade" id="endereco_tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Atualizar Endereço</h5>
                                <form id="update-endereco-form">
                                    @csrf
                                    <input type="hidden" name="aluno_id" value="{{ Auth::user()->id }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cep">CEP</label>
                                                <input type="text" id="cep" name="cep" class="form-control" value="{{ $model->endereco->cep ?? '' }}" placeholder="Ex: 12345-678">
                                                <span class="text-danger error-text cep_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="endereco">Endereço</label>
                                                <input type="text" id="endereco" name="endereco" class="form-control" value="{{ $model->endereco->endereco ?? '' }}" placeholder="Ex: Rua Exemplo, 123">
                                                <span class="text-danger error-text endereco_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cidade">Cidade</label>
                                                <input type="text" id="cidade" name="cidade" class="form-control" value="{{ $model->endereco->cidade ?? '' }}" placeholder="Ex: São Paulo">
                                                <span class="text-danger error-text cidade_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="estado">Estado</label>
                                                <input type="text" id="estado" name="estado" class="form-control" value="{{ $model->endereco->uf ?? '' }}" placeholder="Ex: SP">
                                                <span class="text-danger error-text estado_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pais">País</label>
                                                <input type="text" id="pais" name="pais" class="form-control" value="{{ $model->endereco->pais ?? '' }}" placeholder="Ex: Brasil">
                                                <span class="text-danger error-text pais_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-salvar-endereco">Salvar Alterações</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Tab -->
                    <div class="tab-pane fade" id="password_tab">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Alterar Senha</h5>
                                <form id="update-password-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="old_password">Senha Antiga</label>
                                                <input type="password" id="old_password" name="old_password" class="form-control" placeholder="Digite sua senha atual">
                                                <span class="text-danger error-text old_password_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="new_password">Nova Senha</label>
                                                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Digite a nova senha">
                                                <span class="text-danger error-text new_password_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="new_password_confirmation">Confirmar Senha</label>
                                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Confirme a nova senha">
                                                <span class="text-danger error-text new_password_confirmation_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </form>
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

@push('scripts')
<script>
$(document).ready(function() {

    
    
    // Generic AJAX form submission handler
    function submitForm(formId, url, isFileUpload = false, successMessage = 'Dados atualizados com sucesso!') {
        $(`#${formId}`).on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const formData = isFileUpload ? new FormData(this) : form.serialize();
            const processData = !isFileUpload;
            const contentType = isFileUpload ? false : 'application/x-www-form-urlencoded; charset=UTF-8';

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: processData,
                contentType: contentType,
                success: function(response) {
                    alert(response.message || successMessage);
                    if (response.message === successMessage) {
                        $('.modal').modal('hide');
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
    }

    // Initialize form submissions
    submitForm('update-endereco-form', '/api/aluno/{{ Auth::user()->id }}/update-endereco', false, 'Endereço atualizado com sucesso!');
    submitForm('update-password-form', '/api/aluno/update-password', false, 'Senha atualizada com sucesso!');
});

</script>
@endpush
@endsection