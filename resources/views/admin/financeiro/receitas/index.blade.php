<x-admin.layout title="Receitas">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Receitas"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="mb-4 text-end">
                <a href="{{ route('financeiro.receitas.create') }}" class="btn btn-primary">
                    Lançar Receita
                </a>
            </div>

            <form action="{{ route('financeiro.receitas.index') }}" method="GET" class="mb-3 row g-2">
            <div class="col-md-3">
                <input type="text" name="aluno" class="form-control" placeholder="Nome do Aluno" value="{{ request('aluno') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="descricao" class="form-control" placeholder="Descrição" value="{{ request('descricao') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-control">
                    <option value="">Todos Status</option>
                    <option value="RECEBIDA" {{ request('status') === 'RECEBIDA' ? 'selected' : '' }}>Recebido</option>
                    <option value="PENDENTE" {{ request('status') === 'PENDENTE' ? 'selected' : '' }}>Pendente</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="metodo_pagamento" class="form-control">
                    <option value="">Todos Métodos</option>
                    <option value="PRESENCIAL" {{ request('metodo_pagamento') === 'PRESENCIAL' ? 'selected' : '' }}>Presencial</option>
                    <option value="PIX" {{ request('metodo_pagamento') === 'PIX' ? 'selected' : '' }}>Pix</option>
                    <option value="DINHEIRO" {{ request('metodo_pagamento') === 'DINHEIRO' ? 'selected' : '' }}>Dinheiro</option>
                    <option value="CARTAO" {{ request('metodo_pagamento') === 'CARTAO' ? 'selected' : '' }}>Cartão</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>


            <div class="card shadow">
                <div class="card-body">
                    <!-- Tabela responsiva -->
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Descrição</th>
                                    <th>Serviço/Agendamento</th>
                                    <th>Categoria</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Método</th>
                                    <th>Data Recebimento</th>
                                    <th>Data Vencto</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receitas as $receita)
                                    <tr>
                                        {{-- Aluno: Priorize via pagamento.aluno.usuario, fallback para usuario direto --}}
                                        <td>{{ $receita->usuario->nome }}</td>
                                            <td>{{ $receita->descricao }}</td>
                                        {{-- Serviço: Via pagamento.agendamento.modalidade --}}
                                        <td>{{ $receita->pagamento->agendamento->modalidade->nome ?? '-' }}</td>
                                        <td>{{ $receita->categoria->nome ?? '-' }}</td>
                                        <td>R$ {{ number_format($receita->valor, 2, ',', '.') }}</td>
                                        <td>
                                            @if($receita->status === 'RECEBIDA')
                                                <span class="badge bg-success">Recebido</span>
                                            @else
                                                <span class="badge bg-warning">Pendente</span>
                                            @endif
                                        </td>
                                        <td>{{ $receita->pagamento->metodo_pagamento ?? '-' }}</td>
                                       <td>
                                        {{ $receita->data_recebimento ? \Carbon\Carbon::parse($receita->data_vencimento)->format('d/m/Y') : '-' }}
                                    </td>
                                     <td>
                                        {{ $receita->data_vencimento ? \Carbon\Carbon::parse($receita->data_vencimento)->format('d/m/Y') : '-' }}
                                    </td>

                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                <a href="{{ route('financeiro.receitas.edit', $receita->id) }}" class="btn btn-warning btn-sm">
                                                    Editar
                                                </a>
                                                <form action="{{ route('financeiro.receitas.destroy', $receita->id) }}" method="POST" onsubmit="return confirm('Excluir esta receita?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Nenhuma receita encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                        </table>
                    </div>

                  <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Total Receitas (filtro aplicado):</strong> 
                        R$ {{ number_format($totalReceitas, 2, ',', '.') }}
                    </div>

                    {{ $receitas->links() }}
                </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>