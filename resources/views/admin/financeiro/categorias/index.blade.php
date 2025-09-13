<x-admin.layout title="Categorias">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">

            <!-- Page Header -->
            <x-header.titulo pageTitle="Categorias"/>
            <!-- /Page Header -->

            <x-alert/>

            <div class="card shadow">
                <div class="card-body">
                    <a href="{{ route('financeiro.categorias.create') }}" class="btn btn-primary mb-3">Nova Categoria</a>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categorias as $categoria)
                                <tr>
                                    <td>{{ $categoria->nome }}</td>
                                    <td>{{ $categoria->descricao }}</td>
                                    <td>
                                        <a href="{{ route('financeiro.categorias.edit', $categoria->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('financeiro.categorias.destroy', $categoria->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($categorias->isEmpty())
                        <p class="text-center mt-3">Nenhuma categoria cadastrada.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
