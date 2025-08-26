<x-admin.layout title="Contatos do Site">
    <div class="page-wrapper">
        <div class="content container-fluid" style="padding: 5%">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Contatos</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item active">Contatos</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="text-right mb-3">
                <a href="{{ route('admin.site.contatos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Contato
                </a>
            </div>

            <x-alert/>

            <div class="card card-table">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contatos as $contato)
                                    <tr>
                                        <td>{{ ucfirst($contato->tipo) }}</td>
                                        <td>{{ $contato->valor }}</td>
                                        <td>
                                            <a href="{{ route('admin.site.contatos.edit', $contato->id) }}" class="btn btn-sm btn-warning">Editar</a>

                                            <form action="{{ route('admin.site.contatos.destroy', $contato->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Tem certeza?')" class="btn btn-sm btn-danger">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Nenhum contato cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $contatos->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
