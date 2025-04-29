<x-admin.layout title="Depoimentos do Site">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Depoimentos</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Admin</a></li>
                            <li class="breadcrumb-item active">Depoimentos</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="text-right mb-3">
                <a href="{{ route('admin.site.depoimentos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Depoimento
                </a>
            </div>

            <x-alert/>

            <div class="card card-table">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Nota</th>
                                    <th>Comentário</th>
                                    <th>Foto</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($depoimentos as $depoimento)
                                    <tr>
                                        <td>{{ $depoimento->nome }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $depoimento->nota ? '' : '-o' }} text-warning"></i>
                                            @endfor
                                        </td>
                                        <td>{{ Str::limit($depoimento->comentario, 50) }}</td>
                                        <td>
                                            @if($depoimento->foto)
                                                <img src="{{ asset('storage/' . $depoimento->foto) }}" width="50">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.site.depoimentos.edit', $depoimento->id) }}" class="btn btn-sm btn-warning">Editar</a>

                                            <form action="{{ route('admin.site.depoimentos.destroy', $depoimento->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Tem certeza?')" class="btn btn-sm btn-danger">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum depoimento cadastrado ainda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $depoimentos->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
