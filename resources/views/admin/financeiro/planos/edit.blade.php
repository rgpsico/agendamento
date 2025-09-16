<x-admin.layout title="Editar Plano">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Editar Plano"/>
            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('admin.planos.update', $plano->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Plano</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                value="{{ old('nome', $plano->nome) }}" required>
                            @error('nome')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="number" step="0.01" class="form-control" id="valor" name="valor"
                                value="{{ old('valor', $plano->valor) }}" required>
                            @error('valor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="periodicidade" class="form-label">Periodicidade</label>
                            <select class="form-control" id="periodicidade" name="periodicidade" required>
                                <option value="mensal" {{ old('periodicidade', $plano->periodicidade) == 'mensal' ? 'selected' : '' }}>Mensal</option>
                                <option value="trimestral" {{ old('periodicidade', $plano->periodicidade) == 'trimestral' ? 'selected' : '' }}>Trimestral</option>
                                <option value="anual" {{ old('periodicidade', $plano->periodicidade) == 'anual' ? 'selected' : '' }}>Anual</option>
                            </select>
                            @error('periodicidade')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição (opcional)</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao', $plano->descricao) }}</textarea>
                            @error('descricao')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.planos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
