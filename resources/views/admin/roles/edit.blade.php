<x-admin.layout title="Editar Papel">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <h3>Editar Papel</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nome do Papel</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                </div>

                <div class="form-group mt-3">
                    <label>Permissões</label>
                    @foreach($permissions as $permission)
                        <div class="form-check">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                   id="perm_{{ $permission->id }}"
                                   {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                            <label for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Atualizar Papel</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>

        </div>
    </div>
</x-admin.layout>
