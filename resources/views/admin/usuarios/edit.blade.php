<x-admin.layout title="Editar Usuário">
    <div class="page-wrapper">
         <!-- Mensagens de Sucesso -->
       

     <x-alert-messages />
            <h3>Editar Usuário</h3>

            <form action="{{ route('admin.usuarios.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nome e Email -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" value="{{ old('nome', $user->nome) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Senha -->
             @can('alterar_senha') {{-- ou @can('alterar_senha') se usar permissão --}}
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Deixe em branco para não alterar</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Confirmar Senha</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>
            @endcan


                <!-- Perfis -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Perfis</label>
                            @foreach($perfis as $perfil)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="perfis[]" value="{{ $perfil->id }}"
                                           id="perfil_{{ $perfil->id }}"
                                           {{ $user->perfis->contains($perfil->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perfil_{{ $perfil->id }}">
                                        {{ $perfil->nome }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Roles -->
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
                </div>

                <!-- Permissões Diretas -->
                <div class="row mt-4">
                    <div class="col-md-12">
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

                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-success">Atualizar Usuário</button>
                </div>
            </form>

        </div>
    </div>
</x-admin.layout>
