<x-admin.layout title="Categorias">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Categorias"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <a href="{{ route('financeiro.categorias.create') }}" class="btn btn-primary mb-3">
                        Nova Categoria
                    </a>

                    <!-- Tabela responsiva -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categorias as $categoria)
                                    <tr>
                                        <td>{{ $categoria->nome }}</td>
                                        <td>{{ $categoria->descricao }}</td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                <a href="{{ route('financeiro.categorias.edit', $categoria->id) }}" class="btn btn-sm btn-warning">
                                                    Editar
                                                </a>
                                                <form action="{{ route('financeiro.categorias.destroy', $categoria->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Nenhuma categoria cadastrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
