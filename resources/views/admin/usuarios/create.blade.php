<x-admin.layout title="Criar Usuário">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h3>Criar Usuário</h3>

            <form action="{{ route('admin.usuarios.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Perfis</label>
                    <select name="perfis[]" id="perfis" class="form-control" multiple>
                        @foreach ($perfis as $perfil)
                            <option value="{{ $perfil->nome }}">{{ $perfil->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Permissões Diretas</label>
                    <select name="permissions[]" class="form-control" multiple>
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success mt-3">Criar Usuário</button>
            </form>
        </div>
    </div>

</x-admin.layout>
