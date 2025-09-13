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

            <div class="card shadow">
                <div class="card-body">
                    <!-- Tabela responsiva -->
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Serviço/Agendamento</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Método</th>
                                    <th>Data</th>
                                    <th class="text-center">Ações</th>
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
                                        <td colspan="7" class="text-center">Nenhuma receita encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $receitas->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
