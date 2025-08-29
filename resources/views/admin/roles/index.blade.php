<x-admin.layout title="Listar Papéis">
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header d-flex justify-content-between align-items-center">
                <h3 class="page-title">Papéis</h3>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
                    <i class="fe fe-plus"></i> Criar Papel
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="datatable table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Permissões</th>
                                    <th class="text-center">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->permissions->pluck('name')->implode(', ') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm bg-info-light">
                                                <i class="fe fe-pencil"></i> Editar
                                            </a>
                                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm bg-danger-light" onclick="return confirm('Deseja realmente excluir?')">
                                                    <i class="fe fe-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layout>
