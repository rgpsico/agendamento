<x-admin.layout title="Criar Usuário">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h3>Novo usuário (funcionário)</h3>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nome</label>
                        <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>E-mail (usado para entrar no sistema)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Senha</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                        <small class="text-muted">Mínimo de 6 caracteres. O funcionário pode trocar depois.</small>
                    </div>
                </div>

                @if($podeDefinirAreas)
                    @include('admin.usuarios._areas', ['marcadas' => \App\Support\Modulos::PADRAO_FUNCIONARIO, 'acessoTotal' => false])
                @else
                    <p class="text-muted mt-3">O novo usuário terá acesso a: Agenda e Clientes. Para mudar, peça ao responsável pelo estúdio.</p>
                @endif

                @if($ehMaster)
                    <div class="card mt-4">
                        <div class="card-header"><h5 class="card-title mb-0">Administrador do sistema</h5></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Perfis</label>
                                <select name="perfis[]" id="perfis" class="form-control" multiple>
                                    @foreach ($perfis as $perfil)
                                        <option value="{{ $perfil->nome }}">{{ $perfil->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Permissões diretas (avançado)</label>
                                <select name="permissions[]" class="form-control" multiple>
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-3 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Criar usuário</button>
                </div>
            </form>
        </div>
    </div>

</x-admin.layout>
