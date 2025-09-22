<x-admin.layout title="Editar Plano do Aluno">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Editar Plano do Aluno"/>
            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('alunos.planos.update', $plano->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Plano</label>
                            <input type="text" id="nome" name="nome" class="form-control" value="{{ old('nome', $plano->nome) }}" required>
                            @error('nome')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição (opcional)</label>
                            <textarea id="descricao" name="descricao" class="form-control" rows="3">{{ old('descricao', $plano->descricao) }}</textarea>
                            @error('descricao')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor (R$)</label>
                            <input type="number" id="valor" name="valor" step="0.01" class="form-control" value="{{ old('valor', $plano->valor) }}" required>
                            @error('valor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="duracao_dias" class="form-label">Duração (dias)</label>
                            <input type="number" id="duracao_dias" name="duracao_dias" class="form-control" value="{{ old('duracao_dias', $plano->duracao_dias) }}" required>
                            @error('duracao_dias')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('alunos.planos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Plano</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>          
    </div>
</x-admin.layout>
