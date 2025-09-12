<x-admin.layout title="Receitas">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Receitas"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="mb-4 text-end">
                <a href="{{ route('financeiro.receitas.create') }}" class="btn btn-primary">Lançar Receita</a>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Aluno</th>
                                <th>Serviço/Agendamento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Método</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receitas as $receita)
                                <tr>
                                    <td>{{ $receita->aluno->usuario->nome ?? '-' }}</td>
                                    <td>{{ $receita->agendamento->modalidade->nome ?? '-' }}</td>
                                    <td>R$ {{ number_format($receita->valor, 2, ',', '.') }}</td>
                                    <td>
                                        @if($receita->status === 'RECEIVED')
                                            <span class="badge bg-success">Recebido</span>
                                        @else
                                            <span class="badge bg-warning">Pendente</span>
                                        @endif
                                    </td>
                                    <td>{{ $receita->metodo_pagamento }}</td>
                                    <td>{{ $receita->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('financeiro.receitas.edit', $receita->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('financeiro.receitas.destroy', $receita->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Excluir esta receita?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhuma receita encontrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $receitas->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
