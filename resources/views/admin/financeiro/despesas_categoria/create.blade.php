<x-admin.layout title="Nova Categoria de Despesa">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Nova Categoria de Despesa"/>
            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('despesas_categorias.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Categoria</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}" required>
                            @error('nome')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição (opcional)</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('despesas_categorias.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
