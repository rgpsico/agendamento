<x-admin.layout title="Usuários">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="page-title">Gerenciar Usuários</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Admin</a></li>
                            <li class="breadcrumb-item active">Usuários</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                            <i class="fe fe-plus"></i> Novo Usuário
                        </a>
                    </div>
                </div>
            </div>

            <x-alert-messages />

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                            <th>Tipo</th>
                                            <th>Perfis</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($usuarios as $usuario)
                                            <tr>
                                                <td>{{ $usuario->id }}</td>
                                                <td>{{ $usuario->nome }}</td>
                                                <td>{{ $usuario->email }}</td>
                                                <td>{{ $usuario->tipo_usuario ?? '-' }}</td>
                                                <td>
                                                    @if($usuario->perfis->isNotEmpty())
                                                        {{ $usuario->perfis->pluck('nome')->implode(', ') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        <a href="{{ route('admin.usuarios.edit', $usuario->id) }}"
                                                           class="btn btn-sm bg-info-light">
                                                            <i class="fe fe-pencil"></i> Editar
                                                        </a>

                                                        <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('Deseja excluir este usuário?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm bg-danger-light">
                                                                <i class="fe fe-trash"></i> Excluir
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin.layout>
