<x-admin.layout title="Editar Receita Recorrente">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Editar Receita Recorrente"/>
            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('financeiro.receitas_recorrentes.update', $receitaRecorrente->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                          <input type="text" class="form-control" id="empresa_id" name="empresa_id" value="{{ Auth::user()->empresa->id }}" required>
                    <input type="text" class="form-control" id="usuario_id" name="usuario_id" value="{{  Auth::user()->id }}" required>
                     

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" 
                                value="{{ old('descricao', $receitaRecorrente->descricao) }}" required>
                            @error('descricao')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor (R$)</label>
                            <input type="number" step="0.01" class="form-control" id="valor" name="valor" 
                                value="{{ old('valor', $receitaRecorrente->valor) }}" required>
                            @error('valor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoria</label>
                            <select class="form-control" id="categoria_id" name="categoria_id" required>
                                <option value="">Selecione a categoria</option>
                             @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" 
                                    {{ old('categoria_id', $receitaRecorrente->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach

                            </select>
                            @error('categoria_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="frequencia" class="form-label">Frequência</label>
                            <select class="form-control" id="frequencia" name="frequencia" required>
                                <option value="">Selecione</option>
                                <option value="DIARIA" {{ old('frequencia', $receitaRecorrente->frequencia) == 'DIARIA' ? 'selected' : '' }}>Diária</option>
                                <option value="SEMANAL" {{ old('frequencia', $receitaRecorrente->frequencia) == 'SEMANAL' ? 'selected' : '' }}>Semanal</option>
                                <option value="MENSAL" {{ old('frequencia', $receitaRecorrente->frequencia) == 'MENSAL' ? 'selected' : '' }}>Mensal</option>
                                <option value="ANUAL" {{ old('frequencia', $receitaRecorrente->frequencia) == 'ANUAL' ? 'selected' : '' }}>Anual</option>
                            </select>
                            @error('frequencia')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                                value="{{ old('data_inicio', $receitaRecorrente->data_inicio->format('Y-m-d')) }}" required>
                            @error('data_inicio')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="data_fim" class="form-label">Data Fim (opcional)</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim" 
                                value="{{ old('data_fim', $receitaRecorrente->data_fim ? $receitaRecorrente->data_fim->format('Y-m-d') : '') }}">
                            @error('data_fim')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="ACTIVE" {{ old('status', $receitaRecorrente->status) == 'ACTIVE' ? 'selected' : '' }}>Ativa</option>
                                <option value="INACTIVE" {{ old('status', $receitaRecorrente->status) == 'INACTIVE' ? 'selected' : '' }}>Inativa</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('financeiro.receitas_recorrentes.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
