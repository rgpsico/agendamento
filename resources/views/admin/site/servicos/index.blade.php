<x-admin.layout title="Serviços do Site">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Serviços</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item active">Serviços</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="text-right mb-3">
                <a href="{{ route('admin.site.servicos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Serviço
                </a>
            </div>

            <x-alert/>

            <div class="row">
                <div class="col-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Preço</th>
                                            <th>Imagem</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($servicos as $servico)
                                            <tr>
                                                <td>{{ $servico->titulo }}</td>
                                                <td>R$ {{ number_format($servico->preco, 2, ',', '.') }}</td>
                                                <td>
                                                    @if($servico->imagem)
                                                        <img src="{{ asset('storage/' . $servico->imagem) }}" alt="Imagem" width="50">
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.site.servicos.edit', $servico->id) }}" class="btn btn-sm btn-warning">
                                                        Editar
                                                    </a>

                                                    <form action="{{ route('admin.site.servicos.destroy', $servico->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">
                                                            Excluir
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Nenhum serviço cadastrado ainda.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="mt-3">
                                    {{ $servicos->links() }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
