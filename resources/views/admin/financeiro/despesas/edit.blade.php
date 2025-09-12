<x-admin.layout title="Editar Despesa">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Editar Despesa"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('financeiro.despesas.update', $despesa->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Campos ocultos de IDs, se necessário -->
                        <input type="hidden" name="empresa_id" value="{{ $despesa->empresa_id }}">
                        <input type="hidden" name="usuario_id" value="{{ $despesa->usuario_id }}">

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" value="{{ old('descricao', $despesa->descricao) }}" required>
                            @error('descricao')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor (R$)</label>
                            <input type="text"  class="form-control" id="valor" name="valor" value="{{ old('valor', $despesa->valor) }}" required>
                            @error('valor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" value="{{ old('categoria', $despesa->categoria) }}">
                            @error('categoria')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="PENDING" {{ old('status', $despesa->status) === 'PENDING' ? 'selected' : '' }}>Pendente</option>
                                <option value="PAID" {{ old('status', $despesa->status) === 'PAID' ? 'selected' : '' }}>Pago</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento', $despesa->data_vencimento) }}">
                            @error('data_vencimento')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('financeiro.despesas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
