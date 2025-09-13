<x-admin.layout title="Editar Receita">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Editar Receita"/>
            <!-- /Page Header -->

            <x-alert/>

            <form action="{{ route('financeiro.receitas.update', $receita->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Dados da Receita</h5>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" id="descricao" name="descricao" class="form-control" 
                                   value="{{ old('descricao', $receita->descricao) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="number" step="0.01" id="valor" name="valor" class="form-control" 
                                   value="{{ old('valor', $receita->valor) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                            <input type="date" id="data_vencimento" name="data_vencimento" class="form-control" 
                                   value="{{ old('data_vencimento', $receita->data_vencimento ? $receita->data_vencimento->format('Y-m-d') : '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
                            <select id="metodo_pagamento" name="metodo_pagamento" class="form-control" required>
                                <option value="DINHEIRO" {{ old('metodo_pagamento', $receita->metodo_pagamento) == 'DINHEIRO' ? 'selected' : '' }}>Dinheiro</option>
                                <option value="PIX" {{ old('metodo_pagamento', $receita->metodo_pagamento) == 'PIX' ? 'selected' : '' }}>Pix</option>
                                <option value="CARTAO" {{ old('metodo_pagamento', $receita->metodo_pagamento) == 'CARTAO' ? 'selected' : '' }}>Cartão</option>
                                <option value="BOLETO" {{ old('metodo_pagamento', $receita->metodo_pagamento) == 'BOLETO' ? 'selected' : '' }}>Boleto</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="PENDENTE" {{ old('status', $receita->status) == 'PENDENTE' ? 'selected' : '' }}>Pendente</option>
                                <option value="RECEBIDA" {{ old('status', $receita->status) == 'RECEBIDA' ? 'selected' : '' }}>Recebido</option>
                                
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('financeiro.receitas.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>

        </div>
    </div>
</x-admin.layout>
