<x-admin.layout title="Despesas Recorrentes">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Despesas Recorrentes"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <a href="{{ route('financeiro.despesas_recorrentes.create') }}" class="btn btn-primary mb-3">Nova Despesa Recorrente</a>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Valor (R$)</th>
                                <th>Categoria</th>
                                <th>Frequência</th>
                                <th>Status</th>
                                <th>Data Início</th>
                                <th>Data Fim</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($despesas as $despesa)
                                <tr>
                                    <td>{{ $despesa->descricao }}</td>
                                    <td>{{ number_format($despesa->valor, 2, ',', '.') }}</td>
                                    <td>{{ $despesa->categoria->nome ?? '-' }}</td>
                                    <td>{{ ucfirst(strtolower($despesa->frequencia)) }}</td>
                                    <td>{{ $despesa->status }}</td>
                                    <td>{{ $despesa->data_inicio->format('d/m/Y') }}</td>
                                    <td>{{ $despesa->data_fim ? $despesa->data_fim->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('financeiro.despesas_recorrentes.edit', $despesa->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('financeiro.despesas_recorrentes.destroy', $despesa->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($despesas->isEmpty())
                        <p class="text-center mt-3">Nenhuma despesa recorrente cadastrada.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
