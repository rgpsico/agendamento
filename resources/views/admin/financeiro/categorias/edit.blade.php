<x-admin.layout title="Editar Categoria">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Editar Categoria"/>
            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('financeiro.categorias.update', $categoria->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Categoria</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                value="{{ old('nome', $categoria->nome) }}" required>
                            @error('nome')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição (opcional)</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao', $categoria->descricao) }}</textarea>
                            @error('descricao')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <option value="DESPESA" {{ old('tipo', $categoria->tipo) == 'DESPESA' ? 'selected' : '' }}>Despesa</option>
                                <option value="RECEITA" {{ old('tipo', $categoria->tipo) == 'RECEITA' ? 'selected' : '' }}>Receita</option>
                            </select>
                            @error('tipo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('financeiro.categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
