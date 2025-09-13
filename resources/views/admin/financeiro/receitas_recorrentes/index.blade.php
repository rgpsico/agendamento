<x-admin.layout title="Receitas Recorrentes">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Receitas Recorrentes"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <a href="{{ route('financeiro.receitas_recorrentes.create') }}" class="btn btn-primary mb-3">Nova Receita Recorrente</a>

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
                            @foreach($receitas as $receita)
                                <tr>
                                    <td>{{ $receita->descricao }}</td>
                                    <td>{{ number_format($receita->valor, 2, ',', '.') }}</td>
                                    <td>{{ $receita->categoria->nome ?? '-' }}</td>
                                    <td>{{ ucfirst(strtolower($receita->frequencia)) }}</td>
                                    <td>{{ $receita->status }}</td>
                                    <td>{{ $receita->data_inicio }}</td>
                                    <td>{{ $receita->data_fim ? $receita->data_fim : '-' }}</td>
                                    <td>
                                        <a href="{{ route('financeiro.receitas_recorrentes.edit', $receita->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('financeiro.receitas_recorrentes.destroy', $receita->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($receitas->isEmpty())
                        <p class="text-center mt-3">Nenhuma receita recorrente cadastrada.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
