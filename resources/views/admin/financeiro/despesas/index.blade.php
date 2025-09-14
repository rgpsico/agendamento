<x-admin.layout title="Despesas">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Despesas"/>
            <!-- /Page Header -->

            <x-alert/>

            <!-- Botão lançar despesa -->
            <div class="mb-4 text-end">
                <a href="{{ route('financeiro.despesas.create') }}" class="btn btn-primary">
                    Lançar Despesa
                </a>
            </div>

            <!-- Formulário de filtro -->
            <div class="card mb-4 shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('financeiro.despesas.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Categoria</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">Todas</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Pago</option>
                                <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pendente</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Data Início</label>
                            <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Data Fim</label>
                            <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
                        </div>

                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-50">Filtrar</button>
                            <a href="{{ route('financeiro.despesas.index') }}" class="btn btn-secondary w-50">Limpar</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Total das despesas filtradas -->
            <div class="mb-3">
                <h5>Total das Despesas: <strong>R$ {{ number_format($totalDespesas, 2, ',', '.') }}</strong></h5>
            </div>

            <!-- Tabela de despesas -->
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Categoria</th>
                                    <th>Status</th>
                                    <th>Data de Vencimento</th>
                                    <th>Empresa</th>
                                    <th>Usuário</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($despesas as $despesa)
                                    <tr>
                                        <td>{{ $despesa->descricao }}</td>
                                        <td>R$ {{$despesa->valor }}</td>
                                        <td>{{ $despesa->categoria->nome ?? '-' }}</td>
                                        <td>
                                            @if($despesa->status === 'PAID')
                                                <span class="badge bg-success">Pago</span>
                                            @else
                                                <span class="badge bg-warning">Pendente</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($despesa->data_vencimento)->format('d/m/Y') }}</td>
                                        <td>{{ $despesa->empresa->nome ?? '-' }}</td>
                                        <td>{{ $despesa->usuario->nome ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                <a href="{{ route('financeiro.despesas.edit', $despesa->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                                <form action="{{ route('financeiro.despesas.destroy', $despesa->id) }}" method="POST" onsubmit="return confirm('Excluir esta despesa?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Nenhuma despesa encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-3">
                    <div>
                        <strong>Total Receitas (filtro aplicado):</strong> 
                        R$ {{ number_format($totalDespesas, 2, ',', '.') }}
                    </div>
                        {{ $despesas->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
