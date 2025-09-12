<x-admin.layout title="Despesas">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Despesas"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="mb-4 text-end">
                <a href="{{ route('financeiro.despesas.create') }}" class="btn btn-primary">Lançar Despesa</a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Categoria</th>
                                <th>Status</th>
                                <th>Data de Vencimento</th>
                                <th>Empresa</th>
                                <th>Usuário</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($despesas as $despesa)
                                <tr>
                                    <td>{{ $despesa->descricao }}</td>
                                    <td>R$ {{ $despesa->valor }}</td>
                                    <td>{{ $despesa->categoria ?? '-' }}</td>
                                    <td>
                                        @if($despesa->status === 'PAID')
                                            <span class="badge bg-success">Pago</span>
                                        @else
                                            <span class="badge bg-warning">Pendente</span>
                                        @endif
                                    </td>
                                    <td>{{ $despesa->data_vencimento}}</td>
                                    <td>{{ $despesa->empresa->nome ?? '-' }}</td>
                                    <td>{{ $despesa->usuario->nome ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('financeiro.despesas.edit', $despesa->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('financeiro.despesas.destroy', $despesa->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Excluir esta despesa?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nenhuma despesa encontrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $despesas->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>