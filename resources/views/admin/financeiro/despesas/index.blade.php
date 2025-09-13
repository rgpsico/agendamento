<x-admin.layout title="Despesas">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Despesas"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="mb-4 text-end">
                <a href="{{ route('financeiro.despesas.create') }}" class="btn btn-primary">
                    Lançar Despesa
                </a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <!-- Tabela responsiva -->
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
                                        <td>R$ {{ number_format($despesa->valor, 2, ',', '.') }}</td>
                                        <td>{{ $despesa->categoria ?? '-' }}</td>
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
                                                <a href="{{ route('financeiro.despesas.edit', $despesa->id) }}" class="btn btn-warning btn-sm">
                                                    Editar
                                                </a>
                                                <form action="{{ route('financeiro.despesas.destroy', $despesa->id) }}" method="POST" onsubmit="return confirm('Excluir esta despesa?')">
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
                                        <td colspan="8" class="text-center">Nenhuma despesa encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $despesas->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
