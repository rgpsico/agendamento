<x-admin.layout title="Lançar Receita">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <x-header.titulo pageTitle="Lançar Receita"/>
            <x-alert/>

            <form action="{{ route('financeiro.receitas.store') }}" method="POST">
                @csrf
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Dados da Receita</h5>
                    </div>
                    <div class="card-body">
                        <!-- Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" id="descricao" name="descricao" class="form-control" maxlength="255"
                                   value="{{ old('descricao') }}" placeholder="Ex.: Mensalidade Pilates — novembro">
                        </div>

                        <!-- Aluno -->
                        <div class="mb-3">
                            <label for="aluno_id" class="form-label">Aluno / paciente <small class="text-muted">(opcional)</small></label>
                            <select id="aluno_id" name="aluno_id" class="form-control">
                                <option value="">Sem aluno vinculado</option>
                                @foreach($alunos as $aluno)
                                    <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                                        {{ $aluno->usuario->nome ?? 'Aluno sem nome' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Categoria -->
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoria</label>
                            <select id="categoria_id" name="categoria_id" class="form-control">
                                <option value="">Sem categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Ex.: Pilates, Fisioterapia. Cada serviço já ganha a sua categoria; outras você cria em
                                <a href="{{ route('financeiro.categorias.create') }}">Categorias</a>.</small>
                        </div>

                        <!-- Valor -->
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="number" step="0.01" min="0" id="valor" name="valor" class="form-control" value="{{ old('valor') }}" required>
                        </div>

                        <!-- Método de pagamento -->
                        <div class="mb-3">
                            <label for="metodo_pagamento" class="form-label">Forma de pagamento</label>
                            <select id="metodo_pagamento" name="metodo_pagamento" class="form-control" required>
                                @foreach(\App\Models\Receita::METODOS as $valor => $rotulo)
                                    <option value="{{ $valor }}" {{ old('metodo_pagamento') === $valor ? 'selected' : '' }}>{{ $rotulo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="RECEBIDA" {{ old('status') === 'RECEBIDA' ? 'selected' : '' }}>Recebido</option>
                                <option value="PENDENTE" {{ old('status') === 'PENDENTE' ? 'selected' : '' }}>Pendente</option>
                            </select>
                        </div>

                        <!-- Data de recebimento -->
                        <div class="mb-3">
                            <label for="data_recebimento" class="form-label">Data de recebimento <small class="text-muted">(se já recebeu)</small></label>
                            <input type="date" id="data_recebimento" name="data_recebimento" class="form-control"
                                   value="{{ old('data_recebimento', now()->format('Y-m-d')) }}">
                        </div>

                        <!-- Data de vencimento -->
                        <div class="mb-3">
                            <label for="data_vencimento" class="form-label">Data de vencimento <small class="text-muted">(se aplicável)</small></label>
                            <input type="date" id="data_vencimento" name="data_vencimento" class="form-control" value="{{ old('data_vencimento') }}">
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('financeiro.receitas.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Receita</button>
                </div>
            </form>

        </div>
    </div>
</x-admin.layout>
