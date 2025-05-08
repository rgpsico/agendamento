<x-admin.layout title="Editar Usuário">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Editar Usuário</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.usuarios.index') }}">Usuários</a></li>
                            <li class="breadcrumb-item active">Editar Usuário</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Editar Usuário</h4>
                        </div>
                        <div class="card-body">
                            <x-alert/>

                            <form action="{{ route('admin.usuarios.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Nome</label>
                                            <input type="text" name="nome" class="form-control" 
                                                   value="{{ old('nome', $user->nome) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" 
                                                   value="{{ old('email', $user->email) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Senha</label>
                                            <input type="password" name="password" class="form-control">
                                            <small class="text-muted">Deixe em branco para não alterar</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirmar Senha</label>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Papéis (Roles)</label>
                                            @foreach($roles as $role)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="roles[]" value="{{ $role->name }}"
                                                           id="role_{{ $role->id }}"
                                                           {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Permissões Diretas</label>
                                            @foreach($permissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="permissions[]" value="{{ $permission->name }}"
                                                           id="perm_{{ $permission->id }}"
                                                           {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-end">
                                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Atualizar Usuário</button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-admin.layout>