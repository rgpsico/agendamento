<x-admin.layout title="Lançar Despesa">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Lançar Despesa"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('financeiro.despesas.store') }}" method="POST">
                        @csrf

                        <!-- Campo oculto para empresa_id -->
                        <input type="hidden" name="empresa_id" value="{{ Auth::user()->empresa->id ?? '' }}">

                        <!-- Campo oculto para usuario_id -->
                        <input type="hidden" name="usuario_id" value="{{ Auth::user()->id }}">

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" value="{{ old('descricao') }}" required>
                            @error('descricao')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor (R$)</label>
                            <input type="number" step="0.01" class="form-control" id="valor" name="valor" value="{{ old('valor') }}" required>
                            @error('valor')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                           <div class="mb-3">
                              <label for="categoria_id" class="form-label">Categoria</label>
                            <select id="categoria_id" name="categoria_id" class="form-control">
                                <option value="">Selecione uma categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="PENDING" {{ old('status', 'PENDING') === 'PENDING' ? 'selected' : '' }}>Pendente</option>
                                <option value="PAID" {{ old('status') === 'PAID' ? 'selected' : '' }}>Pago</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ old('data_vencimento') }}">
                            @error('data_vencimento')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('financeiro.despesas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>