<x-admin.layout title="Editar Perfil">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <h3>Editar Perfil</h3>

            <form action="{{ route('admin.perfis.update', $perfil) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" class="form-control" 
                           value="{{ old('nome', $perfil->nome) }}" required>
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <select name="tipo" class="form-control" required>
                        @foreach($tipos as $key => $label)
                            <option value="{{ $key }}" {{ $perfil->tipo === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success mt-3">Atualizar Perfil</button>
                <a href="{{ route('admin.perfis.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
            </form>
        </div>
    </div>
</x-admin.layout>
