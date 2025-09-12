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

                        <!-- Aluno -->
                        <div class="mb-3">
                            <label for="aluno_id" class="form-label">Aluno</label>
                            <select id="aluno_id" name="aluno_id" class="form-control" required>
                                <option value="">Selecione um aluno</option>
                                @foreach($alunos as $aluno)
                                    <option value="{{ $aluno->id }}">
                                        {{ $aluno->usuario->nome ?? 'Aluno sem nome' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Agendamento (opcional) -->
                        <div class="mb-3">
                            <label for="agendamento_id" class="form-label">Agendamento (opcional)</label>
                            <select id="agendamento_id" name="agendamento_id" class="form-control">
                                <option value="">Nenhum</option>
                                @foreach($agendamentos as $agendamento)
                                    <option value="{{ $agendamento->id }}">
                                        {{ $agendamento->aluno->usuario->nome ?? 'Aluno sem nome' }} -
                                        {{ $agendamento->modalidade->nome ?? 'Serviço' }} 
                                        ({{ $agendamento->data_da_aula }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Valor -->
                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor</label>
                            <input type="number" step="0.01" id="valor" name="valor" class="form-control" required>
                        </div>

                        <!-- Método de pagamento -->
                        <div class="mb-3">
                            <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
                            <select id="metodo_pagamento" name="metodo_pagamento" class="form-control" required>
                                <option value="DINHEIRO">Dinheiro</option>
                                <option value="PIX">Pix</option>
                                <option value="CARTAO">Cartão</option>
                                <option value="PRESENCIAL">Presencial</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="PENDING">Pendente</option>
                                <option value="RECEIVED">Recebido</option>
                            </select>
                        </div>

                        <!-- Data de vencimento -->
                        <div class="mb-3">
                            <label for="data_vencimento" class="form-label">Data de Vencimento (se aplicável)</label>
                            <input type="date" id="data_vencimento" name="data_vencimento" class="form-control">
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
